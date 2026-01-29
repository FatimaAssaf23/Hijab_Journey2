<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'teacher_id',
        'class_id',
        'status',
        'started_at',
        'paused_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'paused_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the teacher that owns the schedule.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }

    /**
     * Get the class associated with the schedule.
     */
    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    /**
     * Get the scheduled events for the schedule.
     */
    public function scheduledEvents()
    {
        return $this->hasMany(ScheduledEvent::class, 'schedule_id', 'schedule_id');
    }

    /**
     * Get pending events.
     */
    public function pendingEvents()
    {
        return $this->scheduledEvents()->where('status', 'pending');
    }

    /**
     * Get released events.
     */
    public function releasedEvents()
    {
        return $this->scheduledEvents()->where('status', 'released');
    }

    /**
     * Scope a query to only include active schedules.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include paused schedules.
     */
    public function scopePaused($query)
    {
        return $query->where('status', 'paused');
    }

    /**
     * Pause the schedule.
     */
    public function pause()
    {
        $this->update([
            'status' => 'paused',
            'paused_at' => now(),
        ]);
    }

    /**
     * Resume the schedule.
     */
    public function resume()
    {
        $this->update([
            'status' => 'active',
            'paused_at' => null,
        ]);
    }

    /**
     * Complete the schedule.
     */
    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}
