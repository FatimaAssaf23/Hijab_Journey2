<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\ScheduledEvent;
use App\Models\Level;
use App\Models\Lesson;
use App\Models\Assignment;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleGeneratorService
{
    /**
     * Generate a complete schedule for a teacher based on their lessons.
     *
     * @param int $teacherId
     * @param int|null $classId
     * @param Carbon|null $startDate
     * @return Schedule
     */
    public function generateSchedule(int $teacherId, ?int $classId = null, ?Carbon $startDate = null): Schedule
    {
        $startDate = $startDate ?? Carbon::now();
        
        // Check if schedule already exists
        $existingSchedule = Schedule::where('teacher_id', $teacherId)
            ->where('class_id', $classId)
            ->where('status', '!=', 'completed')
            ->first();
        
        if ($existingSchedule) {
            throw new \Exception('An active schedule already exists for this teacher/class combination.');
        }

        DB::beginTransaction();
        try {
            // Create the schedule
            $schedule = Schedule::create([
                'teacher_id' => $teacherId,
                'class_id' => $classId,
                'status' => 'active',
                'started_at' => $startDate,
            ]);

            // Get all levels with lessons for this teacher
            $levels = Level::with(['lessons' => function($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId)
                      ->orderBy('lesson_order', 'asc');
            }])->orderBy('level_id', 'asc')->get();

            $currentDate = $startDate->copy();
            $events = [];

            // Track level completion for quiz scheduling
            $levelLessonCounts = [];
            $levelReleasedCounts = [];

            foreach ($levels as $level) {
                $levelLessonCounts[$level->level_id] = $level->lessons->count();
                $levelReleasedCounts[$level->level_id] = 0;

                foreach ($level->lessons as $lesson) {
                    // Schedule lesson release
                    $events[] = [
                        'schedule_id' => $schedule->schedule_id,
                        'event_type' => 'lesson',
                        'release_date' => $currentDate->toDateString(),
                        'status' => 'pending',
                        'lesson_id' => $lesson->lesson_id,
                        'level_id' => $level->level_id,
                        'edited_by_admin' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Schedule assignment 2 days after lesson
                    $assignmentDate = $currentDate->copy()->addDays(2);
                    $events[] = [
                        'schedule_id' => $schedule->schedule_id,
                        'event_type' => 'assignment',
                        'release_date' => $assignmentDate->toDateString(),
                        'status' => 'pending',
                        'lesson_id' => $lesson->lesson_id,
                        'level_id' => $level->level_id,
                        'edited_by_admin' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Move to next week for next lesson
                    $currentDate->addWeek();
                    $levelReleasedCounts[$level->level_id]++;
                }

                // Check if all lessons in this level are scheduled
                // Schedule quiz when all lessons in level are released
                if ($levelReleasedCounts[$level->level_id] >= $levelLessonCounts[$level->level_id]) {
                    // Quiz is scheduled on the same date as the last lesson's assignment
                    // Or 2 days after the last lesson (whichever is later)
                    $lastLessonDate = $currentDate->copy()->subWeek();
                    $lastAssignmentDate = $lastLessonDate->copy()->addDays(2);
                    
                    $events[] = [
                        'schedule_id' => $schedule->schedule_id,
                        'event_type' => 'quiz',
                        'release_date' => $lastAssignmentDate->toDateString(),
                        'status' => 'pending',
                        'level_id' => $level->level_id,
                        'edited_by_admin' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Bulk insert events
            if (!empty($events)) {
                ScheduledEvent::insert($events);
            }

            DB::commit();
            
            Log::info("Schedule generated successfully", [
                'schedule_id' => $schedule->schedule_id,
                'teacher_id' => $teacherId,
                'events_count' => count($events),
            ]);

            return $schedule->fresh(['scheduledEvents']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to generate schedule", [
                'teacher_id' => $teacherId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Extend schedule with new lessons/levels.
     *
     * @param Schedule $schedule
     * @return void
     */
    public function extendSchedule(Schedule $schedule): void
    {
        if ($schedule->status !== 'active') {
            return;
        }

        // Get the last scheduled event date
        $lastEvent = $schedule->scheduledEvents()
            ->orderBy('release_date', 'desc')
            ->first();

        $startDate = $lastEvent 
            ? Carbon::parse($lastEvent->release_date)->addWeek()
            : Carbon::now();

        // Get all levels with lessons
        $levels = Level::with(['lessons' => function($query) use ($schedule) {
            $query->where('teacher_id', $schedule->teacher_id)
                  ->orderBy('lesson_order', 'asc');
        }])->orderBy('level_id', 'asc')->get();

        // Get already scheduled lesson IDs
        $scheduledLessonIds = $schedule->scheduledEvents()
            ->where('event_type', 'lesson')
            ->whereNotNull('lesson_id')
            ->pluck('lesson_id')
            ->toArray();

        $currentDate = $startDate->copy();
        $events = [];

        foreach ($levels as $level) {
            foreach ($level->lessons as $lesson) {
                // Skip if already scheduled
                if (in_array($lesson->lesson_id, $scheduledLessonIds)) {
                    continue;
                }

                // Schedule lesson
                $events[] = [
                    'schedule_id' => $schedule->schedule_id,
                    'event_type' => 'lesson',
                    'release_date' => $currentDate->toDateString(),
                    'status' => 'pending',
                    'lesson_id' => $lesson->lesson_id,
                    'level_id' => $level->level_id,
                    'edited_by_admin' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Schedule assignment
                $assignmentDate = $currentDate->copy()->addDays(2);
                $events[] = [
                    'schedule_id' => $schedule->schedule_id,
                    'event_type' => 'assignment',
                    'release_date' => $assignmentDate->toDateString(),
                    'status' => 'pending',
                    'lesson_id' => $lesson->lesson_id,
                    'level_id' => $level->level_id,
                    'edited_by_admin' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $currentDate->addWeek();
            }
        }

        if (!empty($events)) {
            ScheduledEvent::insert($events);
            Log::info("Schedule extended", [
                'schedule_id' => $schedule->schedule_id,
                'new_events_count' => count($events),
            ]);
        }
    }

    /**
     * Preview schedule without creating it.
     *
     * @param int $teacherId
     * @param int|null $classId
     * @param Carbon|null $startDate
     * @return array
     */
    public function previewSchedule(int $teacherId, ?int $classId = null, ?Carbon $startDate = null): array
    {
        $startDate = $startDate ?? Carbon::now();
        
        $levels = Level::with(['lessons' => function($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId)
                  ->orderBy('lesson_order', 'asc');
        }])->orderBy('level_id', 'asc')->get();

        $currentDate = $startDate->copy();
        $preview = [];
        $levelLessonCounts = [];
        $levelReleasedCounts = [];

        foreach ($levels as $level) {
            $levelLessonCounts[$level->level_id] = $level->lessons->count();
            $levelReleasedCounts[$level->level_id] = 0;

            foreach ($level->lessons as $lesson) {
                $preview[] = [
                    'type' => 'lesson',
                    'title' => $lesson->title,
                    'level' => $level->level_name,
                    'date' => $currentDate->toDateString(),
                    'lesson_id' => $lesson->lesson_id,
                    'level_id' => $level->level_id,
                ];

                $assignmentDate = $currentDate->copy()->addDays(2);
                $preview[] = [
                    'type' => 'assignment',
                    'title' => 'Assignment for ' . $lesson->title,
                    'level' => $level->level_name,
                    'date' => $assignmentDate->toDateString(),
                    'lesson_id' => $lesson->lesson_id,
                    'level_id' => $level->level_id,
                ];

                $currentDate->addWeek();
                $levelReleasedCounts[$level->level_id]++;
            }

            // Schedule quiz when level is complete
            if ($levelReleasedCounts[$level->level_id] >= $levelLessonCounts[$level->level_id]) {
                $lastLessonDate = $currentDate->copy()->subWeek();
                $lastAssignmentDate = $lastLessonDate->copy()->addDays(2);
                
                $preview[] = [
                    'type' => 'quiz',
                    'title' => 'Quiz for ' . $level->level_name,
                    'level' => $level->level_name,
                    'date' => $lastAssignmentDate->toDateString(),
                    'level_id' => $level->level_id,
                ];
            }
        }

        return $preview;
    }
}
