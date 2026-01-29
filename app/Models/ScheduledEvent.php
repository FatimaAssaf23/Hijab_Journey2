<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ScheduledEvent extends Model
{
    use HasFactory;

    protected $table = 'schedule_events';
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'schedule_id',
        'event_type',
        'release_date',
        'status',
        'lesson_id',
        'level_id',
        'assignment_id',
        'quiz_id',
        'edited_by_admin',
        'admin_id',
        'admin_notes',
        // Legacy fields for backward compatibility
        'title',
        'description',
        'event_date',
        'event_time',
        'color',
        'is_active',
    ];

    protected $casts = [
        'release_date' => 'date',
        'event_date' => 'date',
        'event_time' => 'datetime',
        'edited_by_admin' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the schedule that owns the event.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }

    /**
     * Get the lesson associated with the event.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }

    /**
     * Get the level associated with the event.
     */
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'level_id');
    }

    /**
     * Get the assignment associated with the event.
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id', 'assignment_id');
    }

    /**
     * Get the quiz associated with the event.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'quiz_id');
    }

    /**
     * Get the admin who edited the event.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }

    /**
     * Scope a query to only include pending events.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include released events.
     */
    public function scopeReleased($query)
    {
        return $query->where('status', 'released');
    }

    /**
     * Scope a query to only include events due today or in the past.
     */
    public function scopeDue($query)
    {
        return $query->where('release_date', '<=', now()->toDateString())
                    ->where('status', 'pending');
    }

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('release_date', '>=', now()->toDateString());
    }

    /**
     * Scope a query to filter by event type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Mark event as released.
     */
    public function markAsReleased()
    {
        $this->update(['status' => 'released']);
    }

    /**
     * Mark event as completed.
     */
    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Check if event is due for release.
     */
    public function isDue()
    {
        return $this->status === 'pending' && 
               $this->release_date <= now()->toDateString();
    }

    /**
     * Get event title (for display).
     */
    public function getEventTitleAttribute()
    {
        if ($this->event_type === 'lesson' && $this->lesson) {
            return $this->lesson->title;
        } elseif ($this->event_type === 'assignment') {
            if ($this->assignment) {
                return $this->assignment->title;
            } elseif ($this->lesson) {
                return 'Assignment: ' . $this->lesson->title;
            }
            return 'Assignment';
        } elseif ($this->event_type === 'quiz') {
            if ($this->quiz) {
                return $this->quiz->title;
            } elseif ($this->level) {
                return 'Quiz: ' . $this->level->level_name;
            }
            return 'Quiz';
        }
        return $this->title ?? 'Scheduled Event';
    }

    /**
     * Get event color based on type.
     */
    public function getEventColorAttribute()
    {
        if ($this->color) {
            return $this->color;
        }
        
        return match($this->event_type) {
            'lesson' => '#3B82F6', // Blue
            'assignment' => '#F59E0B', // Orange
            'quiz' => '#10B981', // Green
            default => '#F472B6', // Pink
        };
    }
}
