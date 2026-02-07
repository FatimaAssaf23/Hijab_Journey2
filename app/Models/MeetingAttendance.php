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
        'entered_code',
        'is_verified',
        'verification_attempts',
    ];

    protected function casts(): array
    {
        return [
            'join_time' => 'datetime',
            'leave_time' => 'datetime',
            'duration_minutes' => 'integer',
            'is_verified' => 'boolean',
            'verification_attempts' => 'integer',
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

}
