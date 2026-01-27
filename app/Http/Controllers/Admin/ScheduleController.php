<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleEvent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = ScheduleEvent::orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->get();
        
        // Group events by month for calendar view
        $eventsByMonth = $events->groupBy(function($event) {
            return Carbon::parse($event->event_date)->format('Y-m');
        });
        
        // Get current month events
        $currentMonth = now()->format('Y-m');
        $currentMonthEvents = $events->filter(function($event) use ($currentMonth) {
            return Carbon::parse($event->event_date)->format('Y-m') === $currentMonth;
        });
        
        return view('admin.schedule.index', compact('events', 'eventsByMonth', 'currentMonthEvents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'event_type' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);

        $event = ScheduleEvent::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'] ?? null,
            'event_type' => $validated['event_type'] ?? 'task',
            'color' => $validated['color'] ?? '#F472B6',
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule event created successfully!',
            'event' => $event
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $event = ScheduleEvent::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'event_type' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);

        $event->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Schedule event updated successfully!',
            'event' => $event
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = ScheduleEvent::findOrFail($id);
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Schedule event deleted successfully!'
        ]);
    }

    /**
     * Toggle event active status
     */
    public function toggleStatus($id)
    {
        $event = ScheduleEvent::findOrFail($id);
        $event->is_active = !$event->is_active;
        $event->save();

        return response()->json([
            'success' => true,
            'message' => 'Event status updated successfully!',
            'event' => $event
        ]);
    }
}
