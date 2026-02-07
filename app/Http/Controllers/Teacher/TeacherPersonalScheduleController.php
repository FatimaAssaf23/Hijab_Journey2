<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherScheduleEvent;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherPersonalScheduleController extends Controller
{
    /**
     * Display the schedule page for teachers showing all their events.
     */
    public function index()
    {
        $teacherId = Auth::id();
        
        // Get all active schedule events for this teacher
        $scheduleEvents = TeacherScheduleEvent::where('teacher_id', $teacherId)
            ->where('is_active', true)
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->get();
        
        // Get all meetings for this teacher
        $meetings = Meeting::where('teacher_id', $teacherId)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->get()
            ->sortBy(function($meeting) {
                $dateTime = $meeting->start_time ?? $meeting->scheduled_at;
                return $dateTime ? Carbon::parse($dateTime)->timestamp : 9999999999;
            })
            ->values();
        
        // Format schedule events for JSON serialization
        $formattedEvents = $scheduleEvents->map(function($event) {
            $eventTime = null;
            if ($event->event_time) {
                $eventTime = is_string($event->event_time) 
                    ? substr($event->event_time, 0, 5)
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
                'source' => 'schedule_event',
            ];
        });
        
        // Format meetings for JSON serialization
        $formattedMeetings = $meetings->map(function($meeting) {
            $meetingDateTime = $meeting->start_time ?? $meeting->scheduled_at;
            $eventDate = null;
            $eventTime = null;
            
            if ($meetingDateTime) {
                $dateTime = Carbon::parse($meetingDateTime);
                $eventDate = $dateTime->format('Y-m-d');
                $eventTime = $dateTime->format('H:i');
            }
            
            return [
                'event_id' => 'meeting_' . $meeting->meeting_id,
                'title' => 'Meeting: ' . $meeting->title,
                'description' => $meeting->description ?? 'Google Meet: ' . ($meeting->google_meet_link ?? 'Link not available'),
                'event_date' => $eventDate,
                'event_time' => $eventTime,
                'event_type' => 'meeting',
                'color' => '#8B5CF6',
                'is_active' => true,
                'source' => 'meeting',
                'meeting_id' => $meeting->meeting_id,
            ];
        });
        
        // Combine and sort all events
        $allFormattedEvents = $formattedEvents->concat($formattedMeetings)->sortBy(function($event) {
            if ($event['event_date'] && $event['event_time']) {
                return $event['event_date'] . ' ' . $event['event_time'];
            }
            return $event['event_date'] ?? '9999-12-31';
        })->values();
        
        // Group all events by month for calendar view
        $allEvents = $scheduleEvents->concat($meetings);
        $eventsByMonth = $allEvents->groupBy(function($event) {
            if ($event instanceof Meeting) {
                $dateTime = $event->start_time ?? $event->scheduled_at;
                return $dateTime ? Carbon::parse($dateTime)->format('Y-m') : '0000-00';
            }
            return Carbon::parse($event->event_date)->format('Y-m');
        });
        
        return view('teacher.personal-schedule.index', [
            'events' => $allFormattedEvents,
            'eventsByMonth' => $eventsByMonth,
        ]);
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $request->merge(['_format' => 'json']);
        
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'event_date' => 'required|date|after_or_equal:today',
                'event_time' => 'nullable|date_format:H:i',
                'event_type' => 'nullable|string|max:50',
                'color' => 'nullable|string|max:7',
            ]);

            $event = TeacherScheduleEvent::create([
                'teacher_id' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'event_date' => $validated['event_date'],
                'event_time' => $validated['event_time'] ?? null,
                'event_type' => $validated['event_type'] ?? 'event',
                'color' => $validated['color'] ?? '#F472B6',
                'is_active' => true,
            ]);

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
     * Update the specified event.
     */
    public function update(Request $request, $id)
    {
        $request->merge(['_format' => 'json']);
        
        try {
            $event = TeacherScheduleEvent::where('event_id', $id)
                ->where('teacher_id', Auth::id())
                ->firstOrFail();
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'event_date' => 'required|date|after_or_equal:today',
                'event_time' => 'nullable|date_format:H:i',
                'event_type' => 'nullable|string|max:50',
                'color' => 'nullable|string|max:7',
            ]);
            
            $event->update($validated);
            $event->refresh();

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
     * Remove the specified event.
     */
    public function destroy($id)
    {
        try {
            $event = TeacherScheduleEvent::where('event_id', $id)
                ->where('teacher_id', Auth::id())
                ->firstOrFail();
            
            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Schedule event deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting event: ' . $e->getMessage()
            ], 500);
        }
    }
}
