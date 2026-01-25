<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $primaryKey = 'meeting_id';

    protected $fillable = [
        'class_id',
        'teacher_id',
        'title',
        'description',
        'google_meet_link',
        'scheduled_at',
        'start_time',
        'end_time',
        'duration_minutes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'duration_minutes' => 'integer',
        ];
    }

    /**
     * Get the student class that owns the meeting.
     */
    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    /**
     * Get the teacher that owns the meeting.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }

    /**
     * Get the attendances for the meeting.
     */
    public function attendances()
    {
        return $this->hasMany(MeetingAttendance::class, 'meeting_id', 'meeting_id');
    }
}
