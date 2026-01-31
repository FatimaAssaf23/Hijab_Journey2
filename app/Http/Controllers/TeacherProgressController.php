<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentClass;
use App\Models\Student;
use App\Models\StudentLessonProgress;
use App\Models\StudentGameProgress;
use App\Models\QuizAttempt;
use App\Models\AssignmentSubmission;
use App\Models\Assignment;
use App\Models\Level;
use App\Models\Lesson;
use App\Models\Grade;
use App\Models\Quiz;
use App\Models\Game;

class TeacherProgressController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'teacher') {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'Unauthorized access.');
        }
        
        // Get selected class from request
        $selectedClassId = $request->get('class_id');
        
        // Get search query from request
        $searchQuery = $request->get('search', '');
        
        // Get all classes taught by this teacher
        // IMPORTANT: Only load students with role 'student' to exclude teachers
        $allClassesForFiltering = StudentClass::where('teacher_id', $user->user_id)
            ->with(['students' => function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('role', 'student');
                });
            }, 'students.user'])
            ->get();
        
        // Filter classes if a specific class is selected
        $classes = $allClassesForFiltering;
        if ($selectedClassId) {
            $selectedClassId = (int) $selectedClassId; // Ensure it's an integer for comparison
            $classes = $allClassesForFiltering->where('class_id', $selectedClassId);
        }
        
        // Get all students from teacher's classes (or filtered class)
        // IMPORTANT: Only show actual students, filter out teachers or users with role != 'student'
        $studentIds = [];
        $classStudents = [];
        foreach ($classes as $class) {
            if ($class->students) {
                foreach ($class->students as $student) {
                    // Ensure the user associated with this student exists and has role 'student'
                    // This prevents teachers from appearing in the student list
                    if ($student->user && $student->user->role === 'student') {
                        // Also verify the student record exists and is valid
                        if ($student->student_id) {
                            $studentIds[] = $student->student_id;
                            $classStudents[$student->student_id] = [
                                'student' => $student,
                                'class' => $class,
                            ];
                        }
                    }
                }
            }
        }
        
        // Get all classes for the dropdown (unfiltered)
        $allClasses = StudentClass::where('teacher_id', $user->user_id)
            ->orderBy('class_name', 'asc')
            ->get();
        
        if (empty($studentIds)) {
            return view('teacher.progress', [
                'classes' => $classes,
                'allClasses' => $allClasses,
                'studentProgress' => [],
                'overallStats' => [
                    'total_students' => 0,
                    'average_lessons_completed' => 0,
                    'average_games_completed' => 0,
                    'average_assignments_submitted' => 0,
                    'average_games_score' => 0,
                    'average_quizzes_score' => 0,
                ],
                'selectedClassId' => $selectedClassId,
                'searchQuery' => $searchQuery,
            ]);
        }
        
        // Get all levels and lessons
        $levels = Level::with(['lessons' => function($query) {
            $query->orderBy('lesson_order', 'asc');
        }])
        ->orderBy('level_number', 'asc')
        ->get();
        
        // Get student progress data
        $studentProgress = [];
        
        foreach ($classStudents as $studentId => $data) {
            $student = $data['student'];
            $class = $data['class'];
            
            // Get lesson progress
            $lessonProgresses = StudentLessonProgress::where('student_id', $studentId)
                ->get()
                ->keyBy('lesson_id');
            
            // Calculate lesson statistics
            $totalLessons = 0;
            $completedLessons = 0;
            $inProgressLessons = 0;
            
            foreach ($levels as $level) {
                foreach ($level->lessons as $lesson) {
                    $totalLessons++;
                    $progress = $lessonProgresses->get($lesson->lesson_id);
                    if ($progress && $progress->status === 'completed') {
                        $completedLessons++;
                    } elseif ($progress && $progress->status === 'in_progress') {
                        $inProgressLessons++;
                    }
                }
            }
            
            $lessonProgressPercentage = $totalLessons > 0 
                ? round(($completedLessons / $totalLessons) * 100, 2) 
                : 0;
            
            $pendingLessons = max(0, $totalLessons - $completedLessons - $inProgressLessons);
            
            // Get games statistics
            // Get total games available for lessons in this class
            $lessonIds = [];
            foreach ($levels as $level) {
                foreach ($level->lessons as $lesson) {
                    $lessonIds[] = $lesson->lesson_id;
                }
            }
            
            // Get the actual game IDs for games in these lessons
            $availableGameIds = Game::whereIn('lesson_id', $lessonIds)->pluck('game_id')->toArray();
            $totalGamesAvailable = count($availableGameIds);
            
            // Only get progress for games that are actually in this class's lessons
            $gamesProgress = StudentGameProgress::where('student_id', $studentId)
                ->whereIn('game_id', $availableGameIds)
                ->get();
            
            $gamesCompleted = $gamesProgress->where('status', 'completed')->count();
            $gamesInProgress = $gamesProgress->where('status', 'in_progress')->count();
            $pendingGames = max(0, $totalGamesAvailable - $gamesCompleted - $gamesInProgress);
            
            $gamesStats = [
                'total' => $totalGamesAvailable,
                'completed' => $gamesCompleted,
                'in_progress' => $gamesInProgress,
                'pending' => $pendingGames,
                'average_score' => $gamesProgress->where('status', 'completed')->avg('score') ?? 0,
            ];
            
            // Get quizzes statistics
            $quizAttempts = QuizAttempt::where('student_id', $studentId)->get();
            
            // Get total quizzes available for this class
            $totalQuizzesAvailable = Quiz::where('class_id', $class->class_id)
                ->where('is_active', true)
                ->count();
            
            $quizzesCompleted = $quizAttempts->whereNotNull('submitted_at')->count();
            $pendingQuizzes = max(0, $totalQuizzesAvailable - $quizzesCompleted);
            
            // Calculate quiz average score - check both QuizAttempt scores and Grade percentages
            $quizScores = [];
            foreach ($quizAttempts->whereNotNull('submitted_at') as $attempt) {
                if ($attempt->score !== null) {
                    $quizScores[] = $attempt->score;
                }
            }
            
            // Also check Grade model for quiz grades (in case teacher graded through Grade system)
            $quizGrades = Grade::where('student_id', $studentId)
                ->whereNotNull('quiz_attempt_id')
                ->get();
            foreach ($quizGrades as $grade) {
                if ($grade->percentage !== null) {
                    $quizScores[] = $grade->percentage;
                } elseif ($grade->max_grade > 0) {
                    $percentage = ($grade->grade_value / $grade->max_grade) * 100;
                    $quizScores[] = $percentage;
                }
            }
            
            $quizAverageScore = count($quizScores) > 0 ? round(array_sum($quizScores) / count($quizScores), 2) : 0;
            
            $quizzesStats = [
                'total_attempts' => $totalQuizzesAvailable,
                'completed' => $quizzesCompleted,
                'pending' => $pendingQuizzes,
                'average_score' => $quizAverageScore,
                'highest_score' => $quizAttempts->max('score') ?? 0,
            ];
            
            // Get assignments statistics
            $assignments = Assignment::where('class_id', $class->class_id)->get();
            $assignmentIds = $assignments->pluck('assignment_id')->toArray();
            
            // Get submissions only for assignments in this class
            $submissions = AssignmentSubmission::where('student_id', $studentId)
                ->whereIn('assignment_id', $assignmentIds)
                ->get();
            
            // Get assignment grades only for submissions that belong to assignments in this class
            $submissionIds = $submissions->pluck('submission_id')->toArray();
            $assignmentGrades = Grade::where('student_id', $studentId)
                ->whereNotNull('assignment_submission_id')
                ->whereIn('assignment_submission_id', $submissionIds)
                ->get();
            
            // Calculate average percentage - if percentage is null, calculate it from grade_value and max_grade
            $assignmentAverageScore = 0;
            if ($assignmentGrades->count() > 0) {
                $totalPercentage = 0;
                $countWithPercentage = 0;
                foreach ($assignmentGrades as $grade) {
                    if ($grade->percentage !== null) {
                        $totalPercentage += $grade->percentage;
                        $countWithPercentage++;
                    } elseif ($grade->max_grade > 0) {
                        // Calculate percentage if not stored
                        $percentage = ($grade->grade_value / $grade->max_grade) * 100;
                        $totalPercentage += $percentage;
                        $countWithPercentage++;
                    }
                }
                $assignmentAverageScore = $countWithPercentage > 0 ? round($totalPercentage / $countWithPercentage, 2) : 0;
            }
            
            $assignmentsStats = [
                'total' => $assignments->count(),
                'submitted' => $submissions->count(),
                'pending' => max(0, $assignments->count() - $submissions->count()),
                'completed_percentage' => $assignments->count() > 0 
                    ? round(($submissions->count() / $assignments->count()) * 100) 
                    : 0,
                'average_score' => $assignmentAverageScore,
            ];
            
            $studentProgress[$studentId] = [
                'student' => $student,
                'student_name' => $student->user->first_name . ' ' . $student->user->last_name,
                'class' => $class,
                'class_name' => $class->class_name,
                'lesson_progress' => [
                    'total_lessons' => $totalLessons,
                    'completed_lessons' => $completedLessons,
                    'in_progress_lessons' => $inProgressLessons,
                    'pending_lessons' => $pendingLessons,
                    'percentage' => $lessonProgressPercentage,
                ],
                'games_stats' => $gamesStats,
                'quizzes_stats' => $quizzesStats,
                'assignments_stats' => $assignmentsStats,
            ];
        }
        
        // Filter students by search query if provided
        if (!empty($searchQuery)) {
            $searchQuery = trim($searchQuery);
            $searchLower = strtolower($searchQuery);
            $filteredStudentProgress = [];
            
            foreach ($studentProgress as $studentId => $data) {
                $studentName = strtolower($data['student_name']);
                $className = strtolower($data['class_name']);
                
                // Check if search matches student name or class name
                if (strpos($studentName, $searchLower) !== false || strpos($className, $searchLower) !== false) {
                    $filteredStudentProgress[$studentId] = $data;
                }
            }
            
            $studentProgress = $filteredStudentProgress;
        }
        
        // Calculate overall statistics after filtering
        $totalStudents = count($studentProgress);
        
        if ($totalStudents > 0) {
            // Calculate average assignment scores
            $assignmentScores = array_filter(array_map(function($sp) { 
                return $sp['assignments_stats']['average_score'] > 0 ? $sp['assignments_stats']['average_score'] : null; 
            }, $studentProgress));
            $overallAssignmentAverage = count($assignmentScores) > 0 
                ? round(array_sum($assignmentScores) / count($assignmentScores), 2) 
                : 0;
            
            // Calculate average quiz scores
            $quizScores = array_filter(array_map(function($sp) { 
                return $sp['quizzes_stats']['average_score'] > 0 ? $sp['quizzes_stats']['average_score'] : null; 
            }, $studentProgress));
            $overallQuizAverage = count($quizScores) > 0 
                ? round(array_sum($quizScores) / count($quizScores), 2) 
                : 0;
            
            // Calculate average games scores
            $gamesScores = array_filter(array_map(function($sp) { 
                return $sp['games_stats']['average_score'] > 0 ? $sp['games_stats']['average_score'] : null; 
            }, $studentProgress));
            $overallGamesAverage = count($gamesScores) > 0 
                ? round(array_sum($gamesScores) / count($gamesScores), 2) 
                : 0;
            
            $overallStats = [
                'total_students' => $totalStudents,
                'average_lessons_completed' => $totalStudents > 0 
                    ? round(array_sum(array_map(function($sp) { return $sp['lesson_progress']['percentage']; }, $studentProgress)) / $totalStudents, 2)
                    : 0,
                'average_games_completed' => $totalStudents > 0
                    ? round(array_sum(array_map(function($sp) { return $sp['games_stats']['completed']; }, $studentProgress)) / $totalStudents, 2)
                    : 0,
                'average_assignments_submitted' => $totalStudents > 0
                    ? round(array_sum(array_map(function($sp) { return $sp['assignments_stats']['submitted']; }, $studentProgress)) / $totalStudents, 2)
                    : 0,
                'average_games_score' => $overallGamesAverage,
                'average_quizzes_score' => $overallQuizAverage,
            ];
        } else {
            $overallStats = [
                'total_students' => 0,
                'average_lessons_completed' => 0,
                'average_games_completed' => 0,
                'average_assignments_submitted' => 0,
                'average_games_score' => 0,
                'average_quizzes_score' => 0,
            ];
        }
        
        return view('teacher.progress', compact('classes', 'allClasses', 'studentProgress', 'overallStats', 'selectedClassId', 'searchQuery'));
    }
}
