<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TeacherScheduleEvent extends Model
{
    protected $primaryKey = 'event_id';
    
    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'event_date',
        'event_time',
        'event_type',
        'color',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'string',
        'is_active' => 'boolean',
    ];

    /**
     * Get the teacher that owns the event.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('event_date', $date);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('event_date', Carbon::today());
    }
}
