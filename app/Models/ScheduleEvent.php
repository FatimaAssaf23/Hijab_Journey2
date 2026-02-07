<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ScheduleEvent extends Model
{
    protected $primaryKey = 'event_id';
    
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'release_date',
        'event_time',
        'event_type',
        'color',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'date',
        'release_date' => 'date',
        'event_time' => 'string', // TIME column, not datetime
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }
}
