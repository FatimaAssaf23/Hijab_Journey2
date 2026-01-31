<?php

namespace App\Http\Controllers;

use App\Services\MLPredictionService;
use App\Models\StudentClass;
use App\Models\Student;
use App\Models\Level;
use App\Models\Grade;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\AssignmentSubmission;
use App\Models\ScheduleEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    protected $mlService;

    public function __construct(MLPredictionService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Show teacher dashboard with risk predictions
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'teacher') {
            return redirect()->route('home')
                ->with('error', 'Unauthorized access.');
        }

        $teacher_id = $user->user_id;

        // Get all levels with their lesson counts (same as lesson management)
        $levels = Level::with('lessons')->orderBy('level_id')->get();
        
        // Prepare data for the chart - start with 0, then add levels
        $levelNames = array_merge(['0'], $levels->pluck('level_name')->toArray());
        $lessonCounts = array_merge([0], $levels->map(function($level) {
            return $level->lessons->count();
        })->toArray());
        
        // Calculate grade distribution (0-100) by number of distinct students
        $gradeRanges = [
            '0-20' => 0,
            '21-40' => 0,
            '41-60' => 0,
            '61-80' => 0,
            '81-100' => 0
        ];
        
        // Get all assignment grades for this teacher with their average percentage per student
        $studentAverages = Grade::where('teacher_id', $teacher_id)
            ->whereNotNull('assignment_submission_id')
            ->whereNotNull('percentage')
            ->selectRaw('student_id, AVG(percentage) as avg_percentage')
            ->groupBy('student_id')
            ->get();
        
        // Count distinct students in each grade range based on their average
        foreach ($studentAverages as $avg) {
            $percentage = $avg->avg_percentage;
            if ($percentage >= 0 && $percentage <= 20) {
                $gradeRanges['0-20']++;
            } elseif ($percentage >= 21 && $percentage <= 40) {
                $gradeRanges['21-40']++;
            } elseif ($percentage >= 41 && $percentage <= 60) {
                $gradeRanges['41-60']++;
            } elseif ($percentage >= 61 && $percentage <= 80) {
                $gradeRanges['61-80']++;
            } elseif ($percentage >= 81 && $percentage <= 100) {
                $gradeRanges['81-100']++;
            }
        }
        
        $gradeRangeLabels = array_keys($gradeRanges);
        $gradeRangeCounts = array_values($gradeRanges);
        
        // Get teacher's classes
        $taughtClasses = StudentClass::where('teacher_id', $user->user_id)
            ->with(['students' => function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('role', 'student');
                });
            }, 'students.user', 'assignments', 'quizzes'])
            ->orderBy('class_name', 'asc')
            ->get();
        
        // Calculate statistics
        $totalClasses = $taughtClasses->count();
        $totalStudents = $taughtClasses->sum(function($class) {
            return $class->students->count();
        });
        $totalAssignments = $taughtClasses->sum(function($class) {
            return $class->assignments->count();
        });
        $totalQuizzes = $taughtClasses->sum(function($class) {
            return $class->quizzes->count();
        });
        
        // Get grades given by this teacher
        $grades = Grade::where('teacher_id', $user->user_id)->get();
        $averageGrade = $grades->where('percentage', '!=', null)->avg('percentage') ?? 0;
        
        // Calculate pending grading (submissions without grades)
        $pendingGrading = 0;
        foreach ($taughtClasses as $class) {
            $classAssignments = Assignment::where('class_id', $class->class_id)->get();
            foreach ($classAssignments as $assignment) {
                $ungradedSubmissions = AssignmentSubmission::where('assignment_id', $assignment->assignment_id)
                    ->whereDoesntHave('grade')
                    ->count();
                $pendingGrading += $ungradedSubmissions;
            }
        }
        
        // Prepare data for class distribution chart (students by class)
        $classNames = $taughtClasses->pluck('class_name')->toArray();
        $studentCountsByClass = $taughtClasses->map(function($class) {
            return $class->students->count();
        })->toArray();
        
        // Prepare data for assignments/quizzes by class chart
        $assignmentsByClass = $taughtClasses->map(function($class) {
            return $class->assignments->count();
        })->toArray();
        $quizzesByClass = $taughtClasses->map(function($class) {
            return $class->quizzes->count();
        })->toArray();
        
        // Prepare data for upcoming scheduled activities (next 6 months)
        $activityOverTime = [];
        $activityOverTimeLabels = [];
        $upcomingAssignments = [];
        $upcomingQuizzes = [];
        
        // Get assignment IDs for this teacher's classes
        $classIds = $taughtClasses->pluck('class_id')->toArray();
        
        // Show next 6 months of scheduled activities
        for ($i = 1; $i <= 6; $i++) {
            $date = now()->addMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            // Upcoming assignments (due dates in this month)
            $monthAssignments = Assignment::whereIn('class_id', $classIds)
                ->whereBetween('due_date', [$monthStart, $monthEnd])
                ->count();
            
            // Upcoming quizzes (due dates in this month)
            $monthQuizzes = Quiz::whereIn('class_id', $classIds)
                ->whereBetween('due_date', [$monthStart, $monthEnd])
                ->count();
            
            $activityOverTimeLabels[] = $date->format('M Y');
            $upcomingAssignments[] = $monthAssignments;
            $upcomingQuizzes[] = $monthQuizzes;
        }
        
        // Get upcoming assignments and quizzes for calendar (next 60 days)
        $upcomingAssignmentsList = collect();
        $upcomingQuizzesList = collect();
        
        if (!empty($classIds)) {
            $upcomingAssignmentsList = Assignment::whereIn('class_id', $classIds)
                ->whereDate('due_date', '>=', now())
                ->whereDate('due_date', '<=', now()->addDays(60))
                ->with('studentClass')
                ->orderBy('due_date')
                ->get()
                ->map(function($assignment) {
                    return [
                        'id' => $assignment->assignment_id,
                        'title' => $assignment->title,
                        'date' => $assignment->due_date->format('Y-m-d'),
                        'type' => 'assignment',
                        'class' => $assignment->studentClass->class_name ?? 'N/A'
                    ];
                });
            
            $upcomingQuizzesList = Quiz::whereIn('class_id', $classIds)
                ->whereDate('due_date', '>=', now())
                ->whereDate('due_date', '<=', now()->addDays(60))
                ->with('studentClass')
                ->orderBy('due_date')
                ->get()
                ->map(function($quiz) {
                    return [
                        'id' => $quiz->quiz_id,
                        'title' => $quiz->title,
                        'date' => $quiz->due_date->format('Y-m-d'),
                        'type' => 'quiz',
                        'class' => $quiz->studentClass->class_name ?? 'N/A'
                    ];
                });
        }
        
        // Get schedule events (active events for all teachers)
        $scheduleEventsList = ScheduleEvent::active()
            ->whereDate('event_date', '>=', now())
            ->whereDate('event_date', '<=', now()->addDays(60))
            ->orderBy('event_date')
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->event_id,
                    'title' => $event->title,
                    'date' => $event->event_date->format('Y-m-d'),
                    'type' => 'schedule',
                    'class' => 'Schedule',
                    'color' => $event->color,
                    'description' => $event->description,
                    'event_time' => $event->event_time ? \Carbon\Carbon::parse($event->event_time)->format('H:i') : null,
                ];
            });
        
        // Combine all events (assignments, quizzes, and schedule events) and group by date for calendar
        $allEvents = $upcomingAssignmentsList->concat($upcomingQuizzesList)->concat($scheduleEventsList);
        $calendarEvents = $allEvents
            ->groupBy('date')
            ->map(function($events, $date) {
                return [
                    'date' => $date,
                    'count' => $events->count(),
                    'assignments' => $events->where('type', 'assignment')->count(),
                    'quizzes' => $events->where('type', 'quiz')->count(),
                    'schedules' => $events->where('type', 'schedule')->count(),
                    'events' => $events->values(), // All events for tooltip display
                    'scheduleEvents' => $events->where('type', 'schedule')->values(), // Schedule events with colors
                ];
            });
        
        // Get first class if available (for backward compatibility with view)
        $class = $taughtClasses->first();

        // Get predictions for ALL students in ALL classes
        $allPredictions = [];
        $allPredictionErrors = [];
        $predictionsByClass = [];
        $apiAvailable = true;
        
        foreach ($taughtClasses as $classItem) {
            try {
                $result = $this->mlService->predictForClass($classItem->class_id);
                
                // Handle new format with error info
                if (isset($result['error']) && $result['error'] === 'api_unavailable') {
                    $apiAvailable = false;
                    $allPredictionErrors[$classItem->class_id] = ['api_unavailable' => $result['message']];
                } else {
                    $classPredictions = $result['predictions'] ?? [];
                    $classErrors = $result['errors'] ?? [];
                    
                    // Add class_id to each prediction for grouping
                    foreach ($classPredictions as $pred) {
                        $pred['class_id'] = $classItem->class_id;
                        $pred['class_name'] = $classItem->class_name;
                        $allPredictions[] = $pred;
                    }
                    
                    // Store predictions by class
                    $predictionsByClass[$classItem->class_id] = [
                        'class_name' => $classItem->class_name,
                        'predictions' => $classPredictions,
                        'errors' => $classErrors
                    ];
                    
                    // Add errors with class info
                    foreach ($classErrors as $error) {
                        $error['class_id'] = $classItem->class_id;
                        $error['class_name'] = $classItem->class_name;
                        $allPredictionErrors[] = $error;
                    }
                    
                    $apiAvailable = $result['api_available'] ?? true;
                }
            } catch (\Exception $e) {
                \Log::error('Failed to get ML predictions for class ' . $classItem->class_id . ': ' . $e->getMessage());
                $allPredictionErrors[$classItem->class_id] = ['exception' => $e->getMessage()];
            }
        }
        
        // For backward compatibility, also set $predictions (all combined)
        $predictions = $allPredictions;
        $predictionErrors = $allPredictionErrors;

        return view('teacher.dashboard', compact(
            'levels', 'levelNames', 'lessonCounts', 'gradeRangeLabels', 'gradeRangeCounts',
            'totalClasses', 'totalStudents', 'totalAssignments', 'totalQuizzes', 'averageGrade', 'pendingGrading',
            'classNames', 'studentCountsByClass', 'assignmentsByClass', 'quizzesByClass',
            'activityOverTimeLabels', 'upcomingAssignments', 'upcomingQuizzes',
            'calendarEvents', 'upcomingAssignmentsList', 'upcomingQuizzesList', 'scheduleEventsList',
            'class', 'predictions', 'predictionErrors', 'apiAvailable', 'taughtClasses', 'predictionsByClass'
        ));
    }

    /**
     * Get prediction for specific student (AJAX)
     */
    public function getStudentRisk($id)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'teacher') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Verify student belongs to teacher's class
        $student = Student::with('studentClass')->find($id);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }

        // Check if student belongs to one of teacher's classes
        $teacherClasses = StudentClass::where('teacher_id', $user->user_id)
            ->pluck('class_id')
            ->toArray();

        if (!in_array($student->class_id, $teacherClasses)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Student does not belong to your class.'
            ], 403);
        }

        $prediction = $this->mlService->predictRisk($id);

        if (!$prediction) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to generate prediction. Student may not have enough data.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'prediction' => $prediction
        ]);
    }

    /**
     * Refresh predictions for class (AJAX)
     */
    public function refreshPredictions($id)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'teacher') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Verify class belongs to teacher
        $class = StudentClass::where('teacher_id', $user->user_id)
            ->where('class_id', $id)
            ->first();

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found or you do not have access to it.'
            ], 404);
        }

        $result = $this->mlService->predictForClass($id);
        
        // Handle new format
        if (isset($result['error']) && $result['error'] === 'api_unavailable') {
            return response()->json([
                'success' => false,
                'error' => 'api_unavailable',
                'message' => $result['message'],
                'predictions' => []
            ], 503);
        }
        
        $predictions = $result['predictions'] ?? $result;

        return response()->json([
            'success' => true,
            'predictions' => $predictions,
            'errors' => $result['errors'] ?? []
        ]);
    }
}
