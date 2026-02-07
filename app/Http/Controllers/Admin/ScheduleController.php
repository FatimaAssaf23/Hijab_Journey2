<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleEvent;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
        
        // Format events for JSON serialization to ensure consistent date format
        $formattedEvents = $events->map(function($event) {
            return [
                'event_id' => $event->event_id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date ? $event->event_date->format('Y-m-d') : null,
                'event_time' => $event->event_time ? (is_string($event->event_time) ? substr($event->event_time, 0, 5) : null) : null,
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
        
        return view('admin.schedule.index', compact('events', 'formattedEvents', 'eventsByMonth', 'currentMonthEvents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Ensure we return JSON even on validation errors
        $request->merge(['_format' => 'json']);
        
        try {
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
                'release_date' => $validated['event_date'], // Set release_date same as event_date for admin-created events
                'event_time' => $validated['event_time'] ?? null,
                'event_type' => $validated['event_type'] ?? 'task',
                'color' => $validated['color'] ?? '#F472B6',
                'is_active' => true,
            ]);

            // Format event for JSON response
            $formattedEvent = [
                'event_id' => $event->event_id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date ? $event->event_date->format('Y-m-d') : null,
                'event_time' => $event->event_time ? (is_string($event->event_time) ? substr($event->event_time, 0, 5) : null) : null,
                'event_type' => $event->event_type,
                'color' => $event->color,
                'is_active' => $event->is_active,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Schedule event created successfully!',
                'event' => $formattedEvent
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Ensure we return JSON even on validation errors
        $request->merge(['_format' => 'json']);
        
        try {
            $event = ScheduleEvent::findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'event_date' => 'required|date',
                'event_time' => 'nullable|date_format:H:i',
                'event_type' => 'nullable|string|max:50',
                'color' => 'nullable|string|max:7',
            ]);

            // Ensure release_date is set (use event_date if not provided)
            $updateData = $validated;
            if (!isset($updateData['release_date'])) {
                $updateData['release_date'] = $validated['event_date'];
            }
            
            $event->update($updateData);
            $event->refresh();

            // Format event for JSON response
            $formattedEvent = [
                'event_id' => $event->event_id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date ? $event->event_date->format('Y-m-d') : null,
                'event_time' => $event->event_time ? (is_string($event->event_time) ? substr($event->event_time, 0, 5) : null) : null,
                'event_type' => $event->event_type,
                'color' => $event->color,
                'is_active' => $event->is_active,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Schedule event updated successfully!',
                'event' => $formattedEvent
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating event: ' . $e->getMessage()
            ], 500);
        }
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
