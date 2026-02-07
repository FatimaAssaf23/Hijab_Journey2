<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TeacherScheduleEvent;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\Meeting;
use App\Models\MeetingEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentScheduleController extends Controller
{
    /**
     * Format an event with time calculations
     */
    private function formatEvent($item, $now, $today, $type = 'event', $title = null, $description = null, $color = null, $teacherName = null)
    {
        $eventTime = null;
        $eventDateTime = null;
        $isUpcoming = false;
        $isPast = false;
        $timeUntil = null;
        $timeAgo = null;
        $roundedMinutes = null;
        $secondsUntilNextUpdate = null;
        
        // Handle different types of items
        if ($type === 'assignment' || $type === 'quiz') {
            // For assignments and quizzes, use due_date
            $dueDate = $item->due_date;
            if ($dueDate) {
                $eventDateTime = Carbon::parse($dueDate);
                $eventTime = $eventDateTime->format('H:i');
                
                // Compare with current time
                $isUpcoming = $eventDateTime->isFuture();
                $isPast = $eventDateTime->isPast();
                
                if ($isUpcoming) {
                    $totalMinutes = $now->diffInMinutes($eventDateTime);
                    $roundedMinutes = floor($totalMinutes / 5) * 5;
                    
                    if ($roundedMinutes >= 60) {
                        $hours = floor($roundedMinutes / 60);
                        $minutes = $roundedMinutes % 60;
                        if ($minutes > 0) {
                            $timeUntil = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                        } else {
                            $timeUntil = $hours . ' hour' . ($hours > 1 ? 's' : '');
                        }
                    } else {
                        $timeUntil = $roundedMinutes . ' minute' . ($roundedMinutes > 1 ? 's' : '');
                    }
                    
                    $secondsUntilNextUpdate = (5 - ($totalMinutes % 5)) * 60;
                } elseif ($isPast) {
                    $timeAgo = $eventDateTime->diffForHumans($now, true);
                }
            }
        } elseif ($type === 'meeting') {
            // For meetings, use start_time or scheduled_at
            $meetingDateTime = $item->start_time ?? $item->scheduled_at;
            if ($meetingDateTime) {
                $eventDateTime = Carbon::parse($meetingDateTime);
                $eventTime = $eventDateTime->format('H:i');
                
                // Compare with current time
                $isUpcoming = $eventDateTime->isFuture();
                $isPast = $eventDateTime->isPast();
                
                if ($isUpcoming) {
                    $totalMinutes = $now->diffInMinutes($eventDateTime);
                    $roundedMinutes = floor($totalMinutes / 5) * 5;
                    
                    if ($roundedMinutes >= 60) {
                        $hours = floor($roundedMinutes / 60);
                        $minutes = $roundedMinutes % 60;
                        if ($minutes > 0) {
                            $timeUntil = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                        } else {
                            $timeUntil = $hours . ' hour' . ($hours > 1 ? 's' : '');
                        }
                    } else {
                        $timeUntil = $roundedMinutes . ' minute' . ($roundedMinutes > 1 ? 's' : '');
                    }
                    
                    $secondsUntilNextUpdate = (5 - ($totalMinutes % 5)) * 60;
                } elseif ($isPast) {
                    $timeAgo = $eventDateTime->diffForHumans($now, true);
                }
            }
        } else {
            // For TeacherScheduleEvent
            if ($item->event_time) {
                $timeString = is_string($item->event_time) 
                    ? $item->event_time 
                    : (string)$item->event_time;
                
                $eventTime = substr($timeString, 0, 5);
                
                try {
                    $timeParts = explode(':', $timeString);
                    $hour = (int)($timeParts[0] ?? 0);
                    $minute = (int)($timeParts[1] ?? 0);
                    
                    if ($hour < 0 || $hour > 23) $hour = 0;
                    if ($minute < 0 || $minute > 59) $minute = 0;
                    
                    // Use the actual event_date from the model if available, otherwise use today
                    $eventDate = $item->event_date ? Carbon::parse($item->event_date) : $today;
                    $eventDateTime = $eventDate->copy()->setTime($hour, $minute, 0);
                    $isUpcoming = $eventDateTime->isFuture();
                    $isPast = $eventDateTime->isPast();
                    
                    if ($isUpcoming) {
                        $totalMinutes = $now->diffInMinutes($eventDateTime);
                        $roundedMinutes = floor($totalMinutes / 5) * 5;
                        
                        if ($roundedMinutes >= 60) {
                            $hours = floor($roundedMinutes / 60);
                            $minutes = $roundedMinutes % 60;
                            if ($minutes > 0) {
                                $timeUntil = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                            } else {
                                $timeUntil = $hours . ' hour' . ($hours > 1 ? 's' : '');
                            }
                        } else {
                            $timeUntil = $roundedMinutes . ' minute' . ($roundedMinutes > 1 ? 's' : '');
                        }
                        
                        $secondsUntilNextUpdate = (5 - ($totalMinutes % 5)) * 60;
                    } elseif ($isPast) {
                        $timeAgo = $eventDateTime->diffForHumans($now, true);
                    }
                } catch (\Exception $e) {
                    \Log::error('Event time parsing error: ' . $e->getMessage());
                }
            }
        }
        
        $eventId = match($type) {
            'event' => $item->event_id,
            'assignment' => 'assignment_' . $item->assignment_id,
            'quiz' => 'quiz_' . $item->quiz_id,
            'meeting' => 'meeting_' . $item->meeting_id,
            default => null,
        };
        
        // Format date for display
        $eventDateFormatted = null;
        $eventDateDisplay = null;
        if ($eventDateTime) {
            if ($eventDateTime->isToday()) {
                $eventDateDisplay = 'Today';
            } elseif ($eventDateTime->isTomorrow()) {
                $eventDateDisplay = 'Tomorrow';
            } elseif ($eventDateTime->isYesterday()) {
                $eventDateDisplay = 'Yesterday';
            } else {
                $eventDateDisplay = $eventDateTime->format('M d, Y');
            }
            $eventDateFormatted = $eventDateTime->format('Y-m-d');
        }
        
        return [
            'event_id' => $eventId,
            'title' => $title ?? $item->title,
            'description' => $description ?? $item->description,
            'event_time' => $eventTime,
            'event_datetime' => $eventDateTime,
            'event_date_formatted' => $eventDateFormatted,
            'event_date_display' => $eventDateDisplay,
            'event_type' => $type === 'event' ? $item->event_type : $type,
            'color' => $color ?? ($item->color ?? match($type) {
                'assignment' => '#4ECDC4',
                'quiz' => '#FF6B6B',
                'meeting' => '#8B5CF6',
                default => '#F472B6',
            }),
            'teacher_name' => $teacherName ?? ($item->teacher ? $item->teacher->first_name . ' ' . $item->teacher->last_name : 'Unknown'),
            'is_upcoming' => $isUpcoming,
            'is_past' => $isPast,
            'time_until' => $timeUntil,
            'rounded_minutes' => $roundedMinutes ?? null,
            'event_datetime_iso' => $eventDateTime ? $eventDateTime->toIso8601String() : null,
            'seconds_until_next_update' => $secondsUntilNextUpdate ?? null,
            'time_ago' => $timeAgo,
        ];
    }

    /**
     * Display today's schedule events for students.
     * Students only see events for the current day.
     */
    public function index()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        
        // Get student's class_id
        $student = Auth::user()->student;
        $classId = $student ? $student->class_id : null;
        
        if (!$classId) {
            // If student has no class, return empty schedule
            return view('student.schedule.index', [
                'events' => collect(),
                'today' => $today,
                'nextEvent' => null,
                'next3Days' => [],
                'now' => $now,
            ]);
        }
        
        // Define start and end of day for date comparisons
        $startOfDay = $today->copy()->startOfDay();
        $endOfDay = $today->copy()->endOfDay();
        $tomorrow = $today->copy()->addDay();
        $endOfTomorrow = $tomorrow->copy()->endOfDay();
        
        // Get all active events for today and tomorrow from all teachers
        $scheduleEvents = TeacherScheduleEvent::where('is_active', true)
            ->where(function($query) use ($today, $tomorrow) {
                $query->whereDate('event_date', $today)
                      ->orWhereDate('event_date', $tomorrow);
            })
            ->orderBy('event_time', 'asc')
            ->with('teacher')
            ->get();
        
        // Get assignments for today and tomorrow only for main view
        $assignments = Assignment::where('class_id', $classId)
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$startOfDay, $endOfTomorrow])
            ->with('teacher')
            ->get();
        
        // Get quizzes for today and tomorrow only for main view
        $quizzes = Quiz::where('class_id', $classId)
            ->where('is_active', true)
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$startOfDay, $endOfTomorrow])
            ->with('teacher')
            ->orderBy('due_date', 'asc')
            ->get();
        
        // Get meetings where student is enrolled for today and tomorrow
        $studentId = Auth::id();
        $enrolledMeetingIds = MeetingEnrollment::where('student_id', $studentId)
            ->pluck('meeting_id');
        
        $meetings = Meeting::whereIn('meeting_id', $enrolledMeetingIds)
            ->where(function($query) use ($startOfDay, $endOfTomorrow) {
                $query->where(function($q) use ($startOfDay, $endOfTomorrow) {
                    $q->whereNotNull('start_time')
                      ->whereBetween('start_time', [$startOfDay, $endOfTomorrow]);
                })->orWhere(function($q) use ($startOfDay, $endOfTomorrow) {
                    $q->whereNull('start_time')
                      ->whereNotNull('scheduled_at')
                      ->whereBetween('scheduled_at', [$startOfDay, $endOfTomorrow]);
                });
            })
            ->with('teacher')
            ->get()
            ->sortBy(function($meeting) {
                $dateTime = $meeting->start_time ?? $meeting->scheduled_at;
                return $dateTime ? Carbon::parse($dateTime)->timestamp : 9999999999;
            })
            ->values();
        
        // Format all events
        $formattedEvents = collect();
        
        // Format schedule events
        foreach ($scheduleEvents as $event) {
            $formattedEvents->push($this->formatEvent($event, $now, $today, 'event'));
        }
        
        // Format assignments
        foreach ($assignments as $assignment) {
            $formattedEvents->push($this->formatEvent(
                $assignment,
                $now,
                $today,
                'assignment',
                'Assignment Deadline: ' . $assignment->title,
                $assignment->description,
                '#4ECDC4',
                $assignment->teacher ? $assignment->teacher->first_name . ' ' . $assignment->teacher->last_name : 'Unknown'
            ));
        }
        
        // Format quizzes - only show today's and tomorrow's quizzes in main view
        foreach ($quizzes as $quiz) {
            if ($quiz->due_date) {
                $formattedEvents->push($this->formatEvent(
                    $quiz,
                    $now,
                    $today,
                    'quiz',
                    'Quiz Deadline: ' . $quiz->title,
                    $quiz->description,
                    '#FF6B6B',
                    $quiz->teacher ? $quiz->teacher->first_name . ' ' . $quiz->teacher->last_name : 'Unknown'
                ));
            }
        }
        
        // Format meetings
        foreach ($meetings as $meeting) {
            $formattedEvents->push($this->formatEvent(
                $meeting,
                $now,
                $today,
                'meeting',
                'Meeting: ' . $meeting->title,
                $meeting->description ?? 'Google Meet: ' . ($meeting->google_meet_link ?? 'Link not available'),
                '#8B5CF6',
                $meeting->teacher ? $meeting->teacher->first_name . ' ' . $meeting->teacher->last_name : 'Unknown'
            ));
        }
        
        // Sort all events by time
        $formattedEvents = $formattedEvents->sortBy(function($event) {
            return $event['event_datetime'] ? $event['event_datetime']->timestamp : 9999999999;
        })->values();
        
        // Get next upcoming event
        $nextEvent = $formattedEvents->filter(function($event) {
            return $event['is_upcoming'] ?? false;
        })->sortBy('event_time')->first();
        
        // Get all upcoming events (beyond tomorrow) for preview section
        // This includes schedule events, assignments, and quizzes due after tomorrow
        $allUpcomingEvents = collect();
        
        // Get upcoming schedule events (beyond tomorrow)
        $upcomingScheduleEvents = TeacherScheduleEvent::where('is_active', true)
            ->whereDate('event_date', '>', $tomorrow)
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->with('teacher')
            ->get();
        
        foreach ($upcomingScheduleEvents as $event) {
            $eventTime = null;
            if ($event->event_time) {
                $eventTime = is_string($event->event_time) 
                    ? substr($event->event_time, 0, 5)
                    : null;
            }
            $allUpcomingEvents->push([
                'date' => Carbon::parse($event->event_date),
                'title' => $event->title,
                'event_time' => $eventTime,
                'color' => $event->color,
                'teacher_name' => $event->teacher ? $event->teacher->first_name . ' ' . $event->teacher->last_name : 'Unknown',
                'type' => 'event',
            ]);
        }
        
        // Get upcoming assignments (beyond tomorrow)
        $upcomingAssignments = Assignment::where('class_id', $classId)
            ->whereNotNull('due_date')
            ->where('due_date', '>', $endOfTomorrow)
            ->with('teacher')
            ->orderBy('due_date', 'asc')
            ->get();
        
        foreach ($upcomingAssignments as $assignment) {
            $dueDate = Carbon::parse($assignment->due_date);
            $allUpcomingEvents->push([
                'date' => $dueDate,
                'title' => 'Assignment Deadline: ' . $assignment->title,
                'event_time' => $dueDate->format('H:i'),
                'color' => '#4ECDC4',
                'teacher_name' => $assignment->teacher ? $assignment->teacher->first_name . ' ' . $assignment->teacher->last_name : 'Unknown',
                'type' => 'assignment',
            ]);
        }
        
        // Get upcoming quizzes (beyond tomorrow)
        $upcomingQuizzes = Quiz::where('class_id', $classId)
            ->where('is_active', true)
            ->whereNotNull('due_date')
            ->where('due_date', '>', $endOfTomorrow)
            ->with('teacher')
            ->orderBy('due_date', 'asc')
            ->get();
        
        foreach ($upcomingQuizzes as $quiz) {
            if ($quiz->due_date) {
                $dueDate = Carbon::parse($quiz->due_date);
                $allUpcomingEvents->push([
                    'date' => $dueDate,
                    'title' => 'Quiz Deadline: ' . $quiz->title,
                    'event_time' => $dueDate->format('H:i'),
                    'color' => '#FF6B6B',
                    'teacher_name' => $quiz->teacher ? $quiz->teacher->first_name . ' ' . $quiz->teacher->last_name : 'Unknown',
                    'type' => 'quiz',
                ]);
            }
        }
        
        // Get upcoming meetings (beyond tomorrow)
        $upcomingMeetings = Meeting::whereIn('meeting_id', $enrolledMeetingIds)
            ->where(function($query) use ($endOfTomorrow) {
                $query->where(function($q) use ($endOfTomorrow) {
                    $q->whereNotNull('start_time')
                      ->where('start_time', '>', $endOfTomorrow);
                })->orWhere(function($q) use ($endOfTomorrow) {
                    $q->whereNull('start_time')
                      ->whereNotNull('scheduled_at')
                      ->where('scheduled_at', '>', $endOfTomorrow);
                });
            })
            ->with('teacher')
            ->get()
            ->sortBy(function($meeting) {
                $dateTime = $meeting->start_time ?? $meeting->scheduled_at;
                return $dateTime ? Carbon::parse($dateTime)->timestamp : 9999999999;
            })
            ->values();
        
        foreach ($upcomingMeetings as $meeting) {
            $meetingDateTime = $meeting->start_time ?? $meeting->scheduled_at;
            if ($meetingDateTime) {
                $meetingDate = Carbon::parse($meetingDateTime);
                $allUpcomingEvents->push([
                    'date' => $meetingDate,
                    'title' => 'Meeting: ' . $meeting->title,
                    'event_time' => $meetingDate->format('H:i'),
                    'color' => '#8B5CF6',
                    'teacher_name' => $meeting->teacher ? $meeting->teacher->first_name . ' ' . $meeting->teacher->last_name : 'Unknown',
                    'type' => 'meeting',
                ]);
            }
        }
        
        // Group upcoming events by date and create preview for next 3 days (starting from day after tomorrow)
        $next3Days = [];
        for ($i = 2; $i <= 4; $i++) { // Day 2 = day after tomorrow, Day 3, Day 4
            $date = $today->copy()->addDays($i);
            $dayEvents = $allUpcomingEvents->filter(function($event) use ($date) {
                return $event['date']->isSameDay($date);
            })->map(function($event) {
                return [
                    'title' => $event['title'],
                    'event_time' => $event['event_time'],
                    'color' => $event['color'],
                    'teacher_name' => $event['teacher_name'],
                ];
            })->sortBy('event_time')->values();
            
            if ($dayEvents->count() > 0) {
                $next3Days[] = [
                    'date' => $date,
                    'date_formatted' => $date->format('M d'),
                    'day_name' => $date->format('l'),
                    'events' => $dayEvents,
                    'count' => $dayEvents->count(),
                ];
            }
        }
        
        return view('student.schedule.index', [
            'events' => $formattedEvents,
            'today' => $today,
            'nextEvent' => $nextEvent,
            'next3Days' => $next3Days,
            'now' => $now,
        ]);
    }
}
