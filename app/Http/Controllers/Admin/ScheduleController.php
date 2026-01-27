<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleEvent;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\ScheduleService;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ScheduleEvent::with(['teacher', 'studentClass'])
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc');
        
        // Filter by teacher if provided
        if ($request->has('teacher_id') && $request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }
        
        // Filter by class if provided
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        
        // Filter by auto-generated status if provided
        if ($request->has('is_auto_generated')) {
            $query->where('is_auto_generated', $request->is_auto_generated);
        }
        
        $events = $query->get();
        
        // Group events by month for calendar view
        $eventsByMonth = $events->groupBy(function($event) {
            return Carbon::parse($event->event_date)->format('Y-m');
        });
        
        // Get current month events
        $currentMonth = now()->format('Y-m');
        $currentMonthEvents = $events->filter(function($event) use ($currentMonth) {
            return Carbon::parse($event->event_date)->format('Y-m') === $currentMonth;
        });
        
        // Get teachers and classes for filter dropdowns
        $teachers = \App\Models\User::where('role', 'teacher')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
        $classes = \App\Models\StudentClass::with('teacher')->orderBy('class_name')->get();
        
        return view('admin.schedule.index', compact('events', 'eventsByMonth', 'currentMonthEvents', 'teachers', 'classes'));
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
            'teacher_id' => 'nullable|integer|exists:users,user_id',
            'class_id' => 'nullable|integer|exists:student_classes,class_id',
            'shift_from' => 'nullable|date_format:H:i',
            'shift_to' => 'nullable|date_format:H:i',
        ]);

        $event = ScheduleEvent::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'] ?? null,
            'event_type' => $validated['event_type'] ?? 'task',
            'color' => $validated['color'] ?? '#F472B6',
            'is_active' => true,
            'is_auto_generated' => false, // Manual entries are never auto-generated
            'teacher_id' => $validated['teacher_id'] ?? null,
            'class_id' => $validated['class_id'] ?? null,
            'shift_from' => $validated['shift_from'] ?? null,
            'shift_to' => $validated['shift_to'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule event created successfully!',
            'event' => $event->load(['teacher', 'studentClass'])
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
            'teacher_id' => 'nullable|integer|exists:users,user_id',
            'class_id' => 'nullable|integer|exists:student_classes,class_id',
            'shift_from' => 'nullable|date_format:H:i',
            'shift_to' => 'nullable|date_format:H:i',
        ]);

        // When admin edits an auto-generated event, mark it as manually modified
        // (but keep is_auto_generated flag for tracking purposes)
        $event->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Schedule event updated successfully!',
            'event' => $event->fresh()->load(['teacher', 'studentClass'])
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

    /**
     * Regenerate auto-schedule for a teacher
     */
    public function regenerateSchedule(Request $request, $teacherId)
    {
        $validated = $request->validate([
            'class_id' => 'nullable|integer|exists:student_classes,class_id',
        ]);

        $scheduleService = new ScheduleService();
        $events = $scheduleService->regenerateScheduleForTeacher(
            $teacherId,
            $validated['class_id'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Schedule regenerated successfully!',
            'events_count' => count($events)
        ]);
    }
}
