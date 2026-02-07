<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $primaryKey = 'meeting_id';

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'meeting_id';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($meeting) {
            if (empty($meeting->verification_code)) {
                $meeting->verification_code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
            }
        });
    }

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
        'verification_code',
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
     * Get the attendances for the meeting (simplified system).
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'meeting_id', 'meeting_id');
    }

    /**
     * Get the enrollments for the meeting.
     */
    public function enrollments()
    {
        return $this->hasMany(MeetingEnrollment::class, 'meeting_id', 'meeting_id');
    }
}
