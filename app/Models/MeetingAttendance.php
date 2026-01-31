<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAttendance extends Model
{
    use HasFactory;

    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'meeting_id',
        'student_id',
        'join_time',
        'leave_time',
        'status',
        'duration_minutes',
        'joined_at',
        'last_confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'join_time' => 'datetime',
            'leave_time' => 'datetime',
            'duration_minutes' => 'integer',
            'joined_at' => 'datetime',
            'last_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Get the meeting that this attendance belongs to.
     */
    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'meeting_id', 'meeting_id');
    }

    /**
     * Get the student that this attendance belongs to.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * Get all attendance check responses for this attendance.
     */
    public function checkResponses()
    {
        return $this->hasMany(AttendanceCheckResponse::class, 'attendance_id', 'attendance_id');
    }

    /**
     * Calculate final attendance status based on all check responses.
     * Returns 'present' if more than 50% of checks were 'present', otherwise 'absent'.
     * Treats 'absent' and 'no_response' the same (both count as not present).
     */
    public function calculateFinalStatus()
    {
        $responses = $this->checkResponses;
        
        if ($responses->isEmpty()) {
            return 'pending'; // No checks yet
        }

        $totalChecks = $responses->count();
        $presentCount = $responses->where('response', 'present')->count();
        $absentCount = $responses->where('response', 'absent')->count();
        $noResponseCount = $responses->where('response', 'no_response')->count();

        // Calculate percentage of present responses
        $presentPercentage = ($presentCount / $totalChecks) * 100;

        // If more than 50% are present, mark as present
        // Otherwise mark as absent (treats absent and no_response the same)
        if ($presentPercentage > 50) {
            return 'present';
        } else {
            return 'absent';
        }
    }
}
