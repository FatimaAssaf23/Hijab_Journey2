<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\ScheduledEvent;
use App\Services\ScheduleEditorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminScheduleController extends Controller
{
    protected $scheduleEditor;

    public function __construct(ScheduleEditorService $scheduleEditor)
    {
        $this->scheduleEditor = $scheduleEditor;
    }

    /**
     * List all schedules from all teachers.
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['teacher', 'studentClass', 'scheduledEvents']);

        // Filters
        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('teacher', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $schedules = $query->orderBy('started_at', 'desc')->paginate(20);

        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * View a specific teacher's schedule.
     */
    public function show($id)
    {
        $schedule = Schedule::with([
            'teacher',
            'studentClass',
            'scheduledEvents' => function($query) {
                $query->orderBy('release_date', 'asc');
            },
            'scheduledEvents.lesson',
            'scheduledEvents.level',
            'scheduledEvents.assignment',
            'scheduledEvents.quiz',
            'scheduledEvents.admin',
        ])->findOrFail($id);

        // Group events by month
        $eventsByMonth = $schedule->scheduledEvents->groupBy(function($event) {
            return \Carbon\Carbon::parse($event->release_date)->format('Y-m');
        });

        return view('admin.schedules.show', compact('schedule', 'eventsByMonth'));
    }

    /**
     * Get event details for editing (AJAX).
     */
    public function getEvent($eventId)
    {
        $event = ScheduledEvent::with(['lesson', 'level', 'assignment', 'quiz', 'admin'])
            ->findOrFail($eventId);

        return response()->json([
            'success' => true,
            'event' => [
                'event_id' => $event->event_id,
                'event_type' => $event->event_type,
                'release_date' => $event->release_date->format('Y-m-d'),
                'status' => $event->status,
                'admin_notes' => $event->admin_notes,
                'edited_by_admin' => $event->edited_by_admin,
            ],
        ]);
    }

    /**
     * Update a scheduled event.
     */
    public function updateEvent(Request $request, $eventId)
    {
        $request->validate([
            'release_date' => 'sometimes|date',
            'event_type' => 'sometimes|in:lesson,assignment,quiz',
            'lesson_id' => 'sometimes|nullable|exists:lessons,lesson_id',
            'level_id' => 'sometimes|nullable|exists:levels,level_id',
            'assignment_id' => 'sometimes|nullable|exists:assignments,assignment_id',
            'quiz_id' => 'sometimes|nullable|exists:quizzes,quiz_id',
            'admin_notes' => 'sometimes|nullable|string',
        ]);

        $event = ScheduledEvent::findOrFail($eventId);
        $adminId = Auth::id();

        try {
            $updatedEvent = $this->scheduleEditor->updateEvent($event, $request->all(), $adminId);

            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully.',
                'event' => $updatedEvent->load(['lesson', 'level', 'assignment', 'quiz', 'admin']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Create a new scheduled event.
     */
    public function createEvent(Request $request, $scheduleId)
    {
        $request->validate([
            'event_type' => 'required|in:lesson,assignment,quiz',
            'release_date' => 'required|date',
            'lesson_id' => 'sometimes|nullable|exists:lessons,lesson_id',
            'level_id' => 'sometimes|nullable|exists:levels,level_id',
            'assignment_id' => 'sometimes|nullable|exists:assignments,assignment_id',
            'quiz_id' => 'sometimes|nullable|exists:quizzes,quiz_id',
            'admin_notes' => 'sometimes|nullable|string',
        ]);

        $schedule = Schedule::findOrFail($scheduleId);
        $adminId = Auth::id();

        try {
            $event = $this->scheduleEditor->createEvent($schedule, $request->all(), $adminId);

            return response()->json([
                'success' => true,
                'message' => 'Event created successfully.',
                'event' => $event->load(['lesson', 'level', 'assignment', 'quiz', 'admin']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete a scheduled event.
     */
    public function deleteEvent(Request $request, $eventId)
    {
        $request->validate([
            'shift_subsequent' => 'sometimes|boolean',
        ]);

        $event = ScheduledEvent::findOrFail($eventId);
        $shiftSubsequent = $request->input('shift_subsequent', false);

        try {
            $this->scheduleEditor->deleteEvent($event, $shiftSubsequent);

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Reorder events.
     */
    public function reorderEvents(Request $request, $scheduleId)
    {
        $request->validate([
            'event_order' => 'required|array',
            'event_order.*' => 'required|date',
        ]);

        $schedule = Schedule::findOrFail($scheduleId);

        try {
            $this->scheduleEditor->reorderEvents($schedule, $request->event_order);

            return response()->json([
                'success' => true,
                'message' => 'Events reordered successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Bulk update events.
     */
    public function bulkUpdate(Request $request, $scheduleId)
    {
        $request->validate([
            'event_ids' => 'required|array',
            'event_ids.*' => 'exists:scheduled_events,event_id',
            'shift_days' => 'sometimes|integer',
            'status' => 'sometimes|in:pending,released,completed',
        ]);

        $schedule = Schedule::findOrFail($scheduleId);
        $adminId = Auth::id();

        $updates = [];
        if ($request->has('shift_days')) {
            $updates['shift_days'] = $request->shift_days;
        }
        if ($request->has('status')) {
            $updates['status'] = $request->status;
        }
        $updates['admin_id'] = $adminId;
        $updates['edited_by_admin'] = true;

        try {
            $count = $this->scheduleEditor->bulkUpdateEvents($schedule, $request->event_ids, $updates);

            return response()->json([
                'success' => true,
                'message' => "{$count} events updated successfully.",
                'updated_count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
