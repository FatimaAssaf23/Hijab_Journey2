<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ScheduleEvent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeacherScheduleController extends Controller
{
    /**
     * Display the schedule page for teachers showing events created by admin.
     */
    public function index()
    {
        // Get all active schedule events created by admin
        $events = ScheduleEvent::where('is_active', true)
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->get();
        
        // Format events for JSON serialization (fix date/time formatting issues)
        $formattedEvents = $events->map(function($event) {
            // Handle event_time - now cast as string, so just get HH:MM part
            $eventTime = null;
            if ($event->event_time) {
                $eventTime = is_string($event->event_time) 
                    ? substr($event->event_time, 0, 5) // Get HH:MM from "HH:MM:SS" or "HH:MM"
                    : null;
            }
            
            return [
                'event_id' => $event->event_id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date ? $event->event_date->format('Y-m-d') : null,
                'event_time' => $eventTime,
                'event_type' => $event->event_type,
                'color' => $event->color,
                'is_active' => $event->is_active,
            ];
        });
        
        // Group events by month for calendar view
        $eventsByMonth = $events->groupBy(function($event) {
            return Carbon::parse($event->event_date)->format('Y-m');
        });
        
        // Get current month events
        $currentMonth = now()->format('Y-m');
        $currentMonthEvents = $events->filter(function($event) use ($currentMonth) {
            return Carbon::parse($event->event_date)->format('Y-m') === $currentMonth;
        });
        
        // Get upcoming events (next 7 days)
        $upcomingEvents = $events->filter(function($event) {
            return Carbon::parse($event->event_date)->isFuture() || 
                   Carbon::parse($event->event_date)->isToday();
        })->take(10);
        
        return view('teacher.schedule.index', [
            'events' => $formattedEvents,
            'eventsByMonth' => $eventsByMonth,
            'currentMonthEvents' => $currentMonthEvents,
            'upcomingEvents' => $upcomingEvents
        ]);
    }
}
