<?php

namespace App\Services;

use App\Models\Meeting;
use App\Models\MeetingEnrollment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MeetingEnrollmentService
{
    /**
     * Sync enrollments for a meeting - ensures all students in the class are enrolled
     * This is the single source of truth for enrollment management
     */
    public function syncEnrollmentsForMeeting(Meeting $meeting): array
    {
        $created = 0;
        $existing = 0;
        
        // If meeting has a class_id, enroll all students in that class
        if ($meeting->class_id) {
            $students = Student::where('class_id', $meeting->class_id)
                ->with('user')
                ->get();
            
            foreach ($students as $student) {
                if ($student->user) {
                    $enrollment = MeetingEnrollment::firstOrCreate(
                        [
                            'meeting_id' => $meeting->meeting_id,
                            'student_id' => $student->user->user_id,
                        ],
                        [
                            'attendance_status' => 'pending',
                        ]
                    );
                    
                    if ($enrollment->wasRecentlyCreated) {
                        $created++;
                    } else {
                        $existing++;
                    }
                }
            }
        }
        
        return [
            'created' => $created,
            'existing' => $existing,
            'total' => $created + $existing,
        ];
    }
    
    /**
     * Sync enrollments for all meetings that have class_id but no enrollments
     * Useful for migrating existing meetings to the new system
     */
    public function syncAllMeetings(): array
    {
        $results = [
            'meetings_processed' => 0,
            'enrollments_created' => 0,
            'errors' => [],
        ];
        
        $meetings = Meeting::whereNotNull('class_id')
            ->whereDoesntHave('enrollments')
            ->get();
        
        foreach ($meetings as $meeting) {
            try {
                $syncResult = $this->syncEnrollmentsForMeeting($meeting);
                $results['meetings_processed']++;
                $results['enrollments_created'] += $syncResult['created'];
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'meeting_id' => $meeting->meeting_id,
                    'error' => $e->getMessage(),
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * Get meetings for a student - handles both enrollment system and class-based fallback
     */
    public function getMeetingsForStudent(User $studentUser): \Illuminate\Database\Eloquent\Collection
    {
        $student = $studentUser->student;
        
        if (!$student) {
            return collect([]);
        }
        
        // First, try to get meetings from enrollments
        $enrollmentMeetingIds = MeetingEnrollment::where('student_id', $studentUser->user_id)
            ->pluck('meeting_id')
            ->toArray();
        
        if (!empty($enrollmentMeetingIds)) {
            return Meeting::whereIn('meeting_id', $enrollmentMeetingIds)
                ->with(['studentClass', 'teacher'])
                ->orderBy('scheduled_at', 'desc')
                ->get();
        }
        
        // Fallback: If no enrollments, sync enrollments for class meetings
        if ($student->class_id) {
            $classMeetings = Meeting::where('class_id', $student->class_id)
                ->with(['studentClass', 'teacher'])
                ->get();
            
            // Auto-sync enrollments for these meetings
            foreach ($classMeetings as $meeting) {
                $this->syncEnrollmentsForMeeting($meeting);
            }
            
            // Return the meetings (now with enrollments)
            return $classMeetings->sortByDesc('scheduled_at')->values();
        }
        
        return collect([]);
    }
    
    /**
     * Get attendance data for a meeting - using simplified system only
     */
    public function getAttendanceDataForMeeting(Meeting $meeting): array
    {
        // Use simplified attendance system
        $attendances = \App\Models\Attendance::where('meeting_id', $meeting->meeting_id)
            ->with(['user'])
            ->orderBy('joined_at', 'ASC')
            ->get();
        
        $allStudents = collect([]);
        if ($meeting->class_id) {
            $allStudents = Student::where('class_id', $meeting->class_id)
                ->with('user')
                ->get();
        }
        
        return [
            'attendances' => $attendances,
            'allStudents' => $allStudents,
            'system' => 'simplified', // Flag to indicate simplified system
        ];
    }
    
    /**
     * Ensure a meeting has enrollments - called before any meeting operation
     */
    public function ensureMeetingHasEnrollments(Meeting $meeting): void
    {
        // Check if table exists first
        if (!\Illuminate\Support\Facades\Schema::hasTable('meeting_enrollments')) {
            return; // Table doesn't exist yet, skip
        }
        
        // If meeting has class_id but no enrollments, create them
        try {
            if ($meeting->class_id && $meeting->enrollments()->count() === 0) {
                $this->syncEnrollmentsForMeeting($meeting);
            }
        } catch (\Exception $e) {
            // Silently fail - will fall back to old system
        }
    }
}
