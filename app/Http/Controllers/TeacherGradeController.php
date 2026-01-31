<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentClass;
use App\Models\Grade;
use App\Models\Student;
use App\Models\QuizAttempt;
use App\Models\StudentGameProgress;
use App\Models\StudentAnswer;

class TeacherGradeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'teacher') {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'Unauthorized access.');
        }
        
        // Get search query and class filter from request
        $searchQuery = $request->get('search', '');
        $selectedClassId = $request->get('class_id', '');
        // Normalize: convert empty string to null for consistent checking
        $selectedClassId = trim($selectedClassId) === '' ? null : $selectedClassId;
        
        // DEBUG: Log the filter state
        \Log::info('TeacherGradeController: Filter state', [
            'selectedClassId' => $selectedClassId,
            'searchQuery' => $searchQuery,
            'isAllClasses' => empty($selectedClassId)
        ]);
        
        // Get all classes taught by this teacher
        // IMPORTANT: Only load students with role 'student' to exclude teachers
        $allClasses = StudentClass::where('teacher_id', $user->user_id)
            ->orderBy('class_name', 'asc')
            ->with(['students' => function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('role', 'student');
                });
            }, 'students.user', 'students.studentClass'])
            ->get();
        
        // Filter classes if a specific class is selected
        $classes = $allClasses;
        if (!empty($selectedClassId)) {
            $selectedClassId = (int) $selectedClassId;
            $classes = $allClasses->where('class_id', $selectedClassId);
        }
        
        // Get all unique user IDs from teacher's classes (to prevent duplicates)
        // Also track which classes each student belongs to
        // IMPORTANT: Use $allClasses to build userClassMap so we capture ALL students from ALL classes
        // This ensures students appear in all their classes even when filtering
        $allUserIds = [];
        $userClassMap = []; // Map user_id => array of class_ids
        foreach ($allClasses as $class) {
            if ($class->students) {
                foreach ($class->students as $student) {
                    if ($student->user && $student->user->role === 'student' && $student->user_id) {
                        $allUserIds[] = $student->user_id;
                        // Track which classes this student belongs to
                        if (!isset($userClassMap[$student->user_id])) {
                            $userClassMap[$student->user_id] = [];
                        }
                        if (!in_array($class->class_id, $userClassMap[$student->user_id])) {
                            $userClassMap[$student->user_id][] = $class->class_id;
                        }
                    }
                }
            }
        }
        
        // Get unique user IDs (remove duplicates) - use ALL students when "All Classes" is selected
        $userIds = array_unique($allUserIds);
        
        // DEBUG: Log user IDs before filtering
        \Log::info('TeacherGradeController: User IDs before filtering', [
            'totalUserIds' => count($userIds),
            'userIds' => array_slice($userIds, 0, 10) // Log first 10
        ]);
        
        // Now filter user IDs based on selected class (if a class is selected)
        if (!empty($selectedClassId)) {
            $selectedClassId = (int) $selectedClassId;
            // Filter to only include students from the selected class
            $filteredUserIds = [];
            foreach ($userClassMap as $userId => $classIds) {
                if (in_array($selectedClassId, $classIds)) {
                    $filteredUserIds[] = $userId;
                }
            }
            $userIds = array_unique($filteredUserIds);
        } else {
            // "All Classes" selected - ensure selectedClassId is null for consistent checking
            $selectedClassId = null;
        }
        
        // DEBUG: Log user IDs after filtering
        \Log::info('TeacherGradeController: User IDs after filtering', [
            'selectedClassId' => $selectedClassId,
            'filteredUserIds' => count($userIds),
            'userIds' => array_slice($userIds, 0, 10) // Log first 10
        ]);
        
        if (empty($userIds)) {
            return view('teacher.grades', [
                'studentGrades' => [], 
                'classes' => $classes, 
                'allClasses' => $allClasses,
                'searchQuery' => $searchQuery,
                'selectedClassId' => $selectedClassId,
                'studentGradesByClass' => [],
                'overallAverageGrade' => 0,
                'studentsWithGrades' => 0
            ]);
        }
        
        // Get all unique students directly from database by user_id to ensure no duplicates
        // IMPORTANT: Get ALL student records for these users, not just one per user
        // This ensures we capture all student_ids that might have grades
        $studentsQuery = Student::whereIn('user_id', $userIds)
            ->with(['user' => function($query) {
                $query->where('role', 'student');
            }, 'studentClass'])
            ->get();
        
        // Group by user_id and take the first student record per user_id for display
        // But also collect ALL student_ids for grade queries
        $uniqueStudents = [];
        $allStudentIdsForUsers = []; // Track all student_ids for these users
        foreach ($studentsQuery as $student) {
            if (!isset($uniqueStudents[$student->user_id])) {
                $uniqueStudents[$student->user_id] = $student;
            }
            // Collect ALL student_ids for this user (important for grade queries)
            if (!isset($allStudentIdsForUsers[$student->user_id])) {
                $allStudentIdsForUsers[$student->user_id] = [];
            }
            if (!in_array($student->student_id, $allStudentIdsForUsers[$student->user_id])) {
                $allStudentIdsForUsers[$student->user_id][] = $student->student_id;
            }
        }
        $students = collect($uniqueStudents);
        
        // Also get users directly to ensure we have all students even if they don't have a student record
        // This handles cases where a student might be in a class but doesn't have a student record yet
        $usersWithoutStudentRecords = [];
        foreach ($userIds as $userId) {
            if (!isset($uniqueStudents[$userId])) {
                // This user is in a class but doesn't have a student record - we'll handle this below
                $usersWithoutStudentRecords[] = $userId;
            }
        }
        
        // Build student data array using user_id as key
        // Ensure no duplicates by checking if user_id already exists
        $studentData = [];
        $studentIds = [];
        $processedUserIds = [];
        
        foreach ($students as $userId => $student) {
            // Only process if user_id hasn't been processed yet (safety check)
            if ($student->user && $student->user->role === 'student' && !in_array($userId, $processedUserIds)) {
                $processedUserIds[] = $userId;
                $studentIds[] = $student->student_id;
                $className = 'N/A';
                if ($student->studentClass) {
                    $className = $student->studentClass->class_name ?? 'N/A';
                } elseif ($student->class_id) {
                    $studentClass = StudentClass::find($student->class_id);
                    $className = $studentClass ? $studentClass->class_name : 'N/A';
                }
                // Only add if not already in array (double safety check)
                if (!isset($studentData[$userId])) {
                    // Get all classes this student belongs to
                    $studentClassIds = $userClassMap[$userId] ?? [$student->class_id];
                    $studentClasses = [];
                    foreach ($studentClassIds as $classId) {
                        $class = $allClasses->firstWhere('class_id', $classId);
                        if ($class) {
                            $studentClasses[] = [
                                'class_id' => $class->class_id,
                                'class_name' => $class->class_name,
                            ];
                        }
                    }
                    
                    $studentData[$userId] = [
                        'student' => $student,
                        'student_id' => $student->student_id,
                        'student_name' => $student->user->first_name . ' ' . $student->user->last_name,
                        'first_name' => $student->user->first_name,
                        'last_name' => $student->user->last_name,
                        'class_name' => $className,
                        'class_ids' => $studentClassIds,
                        'classes' => $studentClasses,
                    ];
                }
            }
        }
        
        // Get unique student IDs (filter out null values for students without records)
        // IMPORTANT: Include ALL student_ids for these users, not just the ones from filtered class
        // This ensures grades are found even if they're associated with a different student_id
        $allStudentIdsForGradeQuery = [];
        foreach ($allStudentIdsForUsers as $userId => $studentIdList) {
            foreach ($studentIdList as $studentId) {
                if ($studentId !== null) {
                    $allStudentIdsForGradeQuery[] = $studentId;
                }
            }
        }
        
        // CRITICAL FIX: Get ALL student records for these users to ensure we have complete student_id list
        // This is especially important when "All Classes" is selected
        // We'll query all student records separately to ensure we don't miss any
        $allStudentRecordsForIds = collect([]);
        if (!empty($userIds)) {
            $allStudentRecordsForIds = Student::whereIn('user_id', $userIds)
                ->get()
                ->pluck('student_id')
                ->filter(function($id) {
                    return $id !== null;
                })
                ->unique()
                ->values()
                ->toArray();
        }
        
        // CRITICAL FIX: When "All Classes" is selected, we MUST use ALL student_ids from database query
        // When a specific class is selected, we can merge with studentIds from studentData
        // This ensures that when viewing all classes, we retrieve grades for ALL student records
        if (empty($selectedClassId)) {
            // "All Classes" selected - use ALL student_ids from database query to ensure we get all grades
            // Merge with allStudentIdsForGradeQuery in case there are any we missed
            $studentIds = array_filter(array_unique(array_merge($allStudentRecordsForIds, $allStudentIdsForGradeQuery)), function($id) {
                return $id !== null;
            });
        } else {
            // Specific class selected - merge with studentIds from studentData (in case there are more)
            $studentIds = array_filter(array_unique(array_merge($studentIds, $allStudentIdsForGradeQuery, $allStudentRecordsForIds)), function($id) {
                return $id !== null;
            });
        }
        
        // DEBUG: Log student IDs being used for grade queries
        \Log::info('TeacherGradeController: Student IDs for grade queries', [
            'selectedClassId' => $selectedClassId,
            'totalStudentIds' => count($studentIds),
            'allStudentRecordsForIds_count' => count($allStudentRecordsForIds),
            'allStudentIdsForGradeQuery_count' => count($allStudentIdsForGradeQuery),
            'studentIds_sample' => array_slice($studentIds, 0, 10) // Log first 10
        ]);
        
        // Check if we have any students (either with student records or in studentData)
        if (empty($studentIds) && empty($studentData)) {
            return view('teacher.grades', [
                'studentGrades' => [], 
                'classes' => $classes, 
                'allClasses' => $allClasses,
                'searchQuery' => $searchQuery,
                'selectedClassId' => $selectedClassId,
                'studentGradesByClass' => [],
                'overallAverageGrade' => 0,
                'studentsWithGrades' => 0
            ]);
        }
        
        // Get all assignment grades for these students
        // Only query if we have student IDs (students with records)
        if (!empty($studentIds)) {
            $assignmentGrades = Grade::whereIn('student_id', $studentIds)
                ->whereNotNull('assignment_submission_id')
                ->with(['student.user', 'student.studentClass', 'assignmentSubmission.assignment'])
                ->orderBy('graded_at', 'desc')
                ->get();
            
            // Get all quiz attempt grades (if they exist in Grade table)
            $quizAttemptGrades = Grade::whereIn('student_id', $studentIds)
                ->whereNotNull('quiz_attempt_id')
                ->with(['student.user', 'student.studentClass', 'quizAttempt.quiz'])
                ->get()
                ->keyBy('quiz_attempt_id'); // Key by quiz_attempt_id for quick lookup
            
            // Get all quiz attempts for these students (even if they don't have Grade records)
            $quizAttempts = QuizAttempt::whereIn('student_id', $studentIds)
                ->whereNotNull('submitted_at')
                ->with(['student.user', 'student.studentClass', 'quiz.questions', 'answers'])
                ->orderBy('submitted_at', 'desc')
                ->get();
            
            // Get all game progress for these students
            $gameProgresses = StudentGameProgress::whereIn('student_id', $studentIds)
                ->where('status', 'completed')
                ->whereNotNull('score')
                ->with(['student.user', 'student.studentClass', 'game.lesson'])
                ->orderBy('completed_at', 'desc')
                ->get();
            
            // DEBUG: Log retrieved grades
            \Log::info('TeacherGradeController: Retrieved grades', [
                'selectedClassId' => $selectedClassId,
                'assignmentGrades_count' => $assignmentGrades->count(),
                'quizAttemptGrades_count' => $quizAttemptGrades->count(),
                'quizAttempts_count' => $quizAttempts->count(),
                'gameProgresses_count' => $gameProgresses->count(),
                'quizAttempts_student_ids' => $quizAttempts->pluck('student_id')->unique()->values()->toArray(),
                'gameProgresses_student_ids' => $gameProgresses->pluck('student_id')->unique()->values()->toArray()
            ]);
        } else {
            // No student IDs, return empty collections
            $assignmentGrades = collect([]);
            $quizAttemptGrades = collect([]);
            $quizAttempts = collect([]);
            $gameProgresses = collect([]);
            
            // DEBUG: Log empty student IDs
            \Log::warning('TeacherGradeController: No student IDs for grade queries', [
                'selectedClassId' => $selectedClassId
            ]);
        }
        
        // Create a map from student_id to user_id for efficient lookups
        // IMPORTANT: A user might have multiple student records (different student_ids) for different classes
        // We need to map ALL student_ids to the user_id so grades are properly associated
        // This is critical when filtering by class - we still need ALL student_ids for grade queries
        $studentIdToUserIdMap = [];
        
        // First, map from studentData
        foreach ($studentData as $userId => $data) {
            $studentId = $data['student_id'];
            if ($studentId && !isset($studentIdToUserIdMap[$studentId])) {
                $studentIdToUserIdMap[$studentId] = $userId;
            }
        }
        
        // Also check all student records in the database for these users
        // This ensures we capture ALL student_ids that might be associated with grades
        // IMPORTANT: Use $userIds (which may be filtered) but get ALL their student records
        $allStudentRecords = collect([]); // Initialize to empty collection
        if (!empty($userIds)) {
            $allStudentRecords = Student::whereIn('user_id', $userIds)
                ->with(['user', 'studentClass'])
                ->get();
            foreach ($allStudentRecords as $studentRecord) {
                $studentId = $studentRecord->student_id;
                $userId = $studentRecord->user_id;
                // Map this student_id to user_id if not already mapped
                // This ensures grades associated with ANY student_id for this user are found
                if ($studentId && !isset($studentIdToUserIdMap[$studentId])) {
                    $studentIdToUserIdMap[$studentId] = $userId;
                }
            }
        }
        
        // Also use the allStudentIdsForUsers we collected earlier
        foreach ($allStudentIdsForUsers as $userId => $studentIdList) {
            foreach ($studentIdList as $studentId) {
                if ($studentId && !isset($studentIdToUserIdMap[$studentId])) {
                    $studentIdToUserIdMap[$studentId] = $userId;
                }
            }
        }
        
        // CRITICAL FIX: Ensure ALL student_ids in $studentIds are mapped to user_ids
        // This is especially important when "All Classes" is selected
        // If a student_id is in $studentIds but not in the map, we need to find its user_id
        foreach ($studentIds as $studentId) {
            if (!isset($studentIdToUserIdMap[$studentId])) {
                // Try to find the student record and get its user_id
                $studentRecord = $allStudentRecords->firstWhere('student_id', $studentId);
                if ($studentRecord && $studentRecord->user_id) {
                    $studentIdToUserIdMap[$studentId] = $studentRecord->user_id;
                } else {
                    // Fallback: query directly if not in $allStudentRecords
                    $studentRecord = Student::find($studentId);
                    if ($studentRecord && $studentRecord->user_id) {
                        $studentIdToUserIdMap[$studentId] = $studentRecord->user_id;
                    }
                }
            }
        }
        
        // Organize all grades by student (using user_id as key to prevent duplicates)
        $studentGrades = [];
        
        // Initialize student entries using user_id as key
        // CRITICAL FIX: Initialize for ALL users in $userIds, not just those in $studentData
        // This ensures grades can be added even if a student isn't in $studentData yet
        // First, initialize from studentData (which has the student model and name)
        foreach ($studentData as $userId => $data) {
            // Only initialize if not already set (safety check)
            if (!isset($studentGrades[$userId])) {
                $studentGrades[$userId] = [
                    'student' => $data['student'],
                    'student_name' => $data['student_name'],
                    'class_name' => $data['class_name'],
                    'grades' => [],
                    'total_grades' => 0,
                    'average_percentage' => 0,
                ];
                
                // DEBUG: Log initialization from studentData
                \Log::debug('TeacherGradeController: Initialized from studentData', [
                    'selectedClassId' => $selectedClassId,
                    'userId' => $userId,
                    'student_name' => $data['student_name']
                ]);
            } else {
                // DEBUG: Log if already initialized (shouldn't happen)
                \Log::warning('TeacherGradeController: studentGrades entry already exists when initializing from studentData', [
                    'selectedClassId' => $selectedClassId,
                    'userId' => $userId,
                    'existing_name' => $studentGrades[$userId]['student_name'] ?? 'N/A',
                    'studentData_name' => $data['student_name']
                ]);
            }
        }
        
        // CRITICAL FIX: Also initialize for any users in $userIds that aren't in $studentData yet
        // This can happen when a user has student records but wasn't captured in $studentData
        // IMPORTANT: Always prefer $studentData if it exists, even if not yet in $studentGrades
        foreach ($userIds as $userId) {
            if (!isset($studentGrades[$userId])) {
                // FIRST: Always check $studentData first (most reliable source)
                if (isset($studentData[$userId])) {
                    $studentGrades[$userId] = [
                        'student' => $studentData[$userId]['student'] ?? null,
                        'student_name' => $studentData[$userId]['student_name'],
                        'class_name' => $studentData[$userId]['class_name'],
                        'grades' => [],
                        'total_grades' => 0,
                        'average_percentage' => 0,
                    ];
                    
                    // DEBUG: Log initialization from studentData (second pass)
                    \Log::debug('TeacherGradeController: Initialized from studentData (second pass)', [
                        'selectedClassId' => $selectedClassId,
                        'userId' => $userId,
                        'student_name' => $studentData[$userId]['student_name']
                    ]);
                } else {
                    // SECOND: Get from allStudentRecords (query by user_id to get correct record)
                    $studentRecord = null;
                    $studentName = 'Unknown Student';
                    $className = 'N/A';
                    
                    // Find any student record for this user
                    if (!empty($allStudentRecords)) {
                        $studentRecord = $allStudentRecords->firstWhere('user_id', $userId);
                    }
                    
                    // If we found a student record, get the name
                    if ($studentRecord && $studentRecord->user) {
                        $studentName = $studentRecord->user->first_name . ' ' . $studentRecord->user->last_name;
                        if ($studentRecord->studentClass) {
                            $className = $studentRecord->studentClass->class_name ?? 'N/A';
                        }
                    } else {
                        // Fallback: try to get from userClassMap and allClasses
                        if (isset($userClassMap[$userId]) && !empty($userClassMap[$userId])) {
                            $firstClassId = $userClassMap[$userId][0];
                            $class = $allClasses->firstWhere('class_id', $firstClassId);
                            if ($class) {
                                $className = $class->class_name;
                            }
                        }
                    }
                    
                    // Initialize the entry so grades can be added
                    $studentGrades[$userId] = [
                        'student' => $studentRecord,
                        'student_name' => $studentName,
                        'class_name' => $className,
                        'grades' => [],
                        'total_grades' => 0,
                        'average_percentage' => 0,
                    ];
                }
            }
        }
        
        // Add assignment grades
        foreach ($assignmentGrades as $grade) {
            // Try to get user_id from the map first
            $userId = $studentIdToUserIdMap[$grade->student_id] ?? null;
            
            // Fallback: if mapping fails, try to get user_id from the grade's student relationship
            if (!$userId && $grade->student && $grade->student->user_id) {
                $userId = $grade->student->user_id;
                // Also add to map for future lookups
                if (!isset($studentIdToUserIdMap[$grade->student_id])) {
                    $studentIdToUserIdMap[$grade->student_id] = $userId;
                }
            }
            
            // DEBUG: Log grade assignment
            \Log::debug('TeacherGradeController: Assigning assignment grade', [
                'selectedClassId' => $selectedClassId,
                'grade_id' => $grade->id ?? 'N/A',
                'student_id' => $grade->student_id,
                'mapped_userId' => $userId,
                'grade_student_user_id' => $grade->student->user_id ?? null,
                'grade_student_name' => $grade->student->user->first_name . ' ' . $grade->student->user->last_name ?? 'N/A'
            ]);
            
            // CRITICAL FIX: If userId exists but studentGrades entry doesn't, initialize it
            // IMPORTANT: Always prefer $studentData as source of truth for student name
            if ($userId && !isset($studentGrades[$userId])) {
                // Initialize on the fly - prefer studentData, then allStudentRecords, then grade relationship
                $studentRecord = null;
                $studentName = 'Unknown Student';
                $className = 'N/A';
                
                // FIRST: Check if we have this user in studentData (most reliable source)
                if (isset($studentData[$userId])) {
                    $studentName = $studentData[$userId]['student_name'];
                    $className = $studentData[$userId]['class_name'];
                    $studentRecord = $studentData[$userId]['student'] ?? null;
                } elseif (!empty($allStudentRecords)) {
                    // SECOND: Get from allStudentRecords (query by user_id to get correct record)
                    $studentRecord = $allStudentRecords->firstWhere('user_id', $userId);
                    if ($studentRecord && $studentRecord->user) {
                        $studentName = $studentRecord->user->first_name . ' ' . $studentRecord->user->last_name;
                        if ($studentRecord->studentClass) {
                            $className = $studentRecord->studentClass->class_name ?? 'N/A';
                        }
                    }
                } elseif ($grade->student && $grade->student->user_id == $userId) {
                    // THIRD: Fallback to grade relationship (only if user_id matches)
                    $studentRecord = $grade->student;
                    if ($grade->student->user) {
                        $studentName = $grade->student->user->first_name . ' ' . $grade->student->user->last_name;
                    }
                    if ($grade->student->studentClass) {
                        $className = $grade->student->studentClass->class_name ?? 'N/A';
                    }
                }
                
                $studentGrades[$userId] = [
                    'student' => $studentRecord,
                    'student_name' => $studentName,
                    'class_name' => $className,
                    'grades' => [],
                    'total_grades' => 0,
                    'average_percentage' => 0,
                ];
            }
            
            if ($userId && isset($studentGrades[$userId])) {
                // CRITICAL FIX: Update student name from $studentData if available (more reliable)
                if (isset($studentData[$userId]) && !empty($studentData[$userId]['student_name'])) {
                    $studentGrades[$userId]['student_name'] = $studentData[$userId]['student_name'];
                    $studentGrades[$userId]['class_name'] = $studentData[$userId]['class_name'];
                    if (!empty($studentData[$userId]['student'])) {
                        $studentGrades[$userId]['student'] = $studentData[$userId]['student'];
                    }
                }
                
                // Calculate percentage and ensure it's within 0-100 range
                $assignmentPercentage = $grade->percentage ?? ($grade->max_grade > 0 ? ($grade->grade_value / $grade->max_grade) * 100 : 0);
                $assignmentPercentage = min(100, max(0, $assignmentPercentage));
                
                $studentGrades[$userId]['grades'][] = (object)[
                    'type' => 'assignment',
                    'grade_value' => $grade->grade_value,
                    'max_grade' => $grade->max_grade ?? 100,
                    'percentage' => round($assignmentPercentage, 2),
                    'feedback' => $grade->feedback,
                    'graded_at' => $grade->graded_at,
                    'item_name' => $grade->assignmentSubmission && $grade->assignmentSubmission->assignment 
                        ? $grade->assignmentSubmission->assignment->title 
                        : 'N/A',
                    'assignment_submission_id' => $grade->assignment_submission_id,
                    'quiz_attempt_id' => null,
                ];
                $studentGrades[$userId]['total_grades']++;
                
                // DEBUG: Log immediately after adding grade
                \Log::debug('TeacherGradeController: Assignment grade ADDED to array', [
                    'selectedClassId' => $selectedClassId,
                    'userId' => $userId,
                    'student_id' => $grade->student_id,
                    'grade_id' => $grade->id ?? 'N/A',
                    'total_grades_now' => $studentGrades[$userId]['total_grades'],
                    'grades_count_now' => count($studentGrades[$userId]['grades']),
                    'student_name' => $studentGrades[$userId]['student_name'] ?? 'N/A'
                ]);
            }
        }
        
        // Add quiz attempts (use Grade record if exists, otherwise use QuizAttempt data)
        foreach ($quizAttempts as $attempt) {
            // Try to get user_id from the map first
            $userId = $studentIdToUserIdMap[$attempt->student_id] ?? null;
            
            // Fallback: if mapping fails, try to get user_id from the attempt's student relationship
            if (!$userId && $attempt->student && $attempt->student->user_id) {
                $userId = $attempt->student->user_id;
                // Also add to map for future lookups
                if (!isset($studentIdToUserIdMap[$attempt->student_id])) {
                    $studentIdToUserIdMap[$attempt->student_id] = $userId;
                }
            }
            
            // DEBUG: Log quiz attempt assignment
            \Log::debug('TeacherGradeController: Assigning quiz attempt', [
                'selectedClassId' => $selectedClassId,
                'attempt_id' => $attempt->attempt_id ?? 'N/A',
                'student_id' => $attempt->student_id,
                'mapped_userId' => $userId,
                'attempt_student_user_id' => $attempt->student->user_id ?? null,
                'attempt_student_name' => $attempt->student->user->first_name . ' ' . $attempt->student->user->last_name ?? 'N/A'
            ]);
            
            // CRITICAL FIX: If userId exists but studentGrades entry doesn't, initialize it
            // IMPORTANT: Always prefer $studentData as source of truth for student name
            if ($userId && !isset($studentGrades[$userId])) {
                // Initialize on the fly - prefer studentData, then allStudentRecords, then attempt relationship
                $studentRecord = null;
                $studentName = 'Unknown Student';
                $className = 'N/A';
                
                // FIRST: Check if we have this user in studentData (most reliable source)
                if (isset($studentData[$userId])) {
                    $studentName = $studentData[$userId]['student_name'];
                    $className = $studentData[$userId]['class_name'];
                    $studentRecord = $studentData[$userId]['student'] ?? null;
                } elseif (!empty($allStudentRecords)) {
                    // SECOND: Get from allStudentRecords (query by user_id to get correct record)
                    $studentRecord = $allStudentRecords->firstWhere('user_id', $userId);
                    if ($studentRecord && $studentRecord->user) {
                        $studentName = $studentRecord->user->first_name . ' ' . $studentRecord->user->last_name;
                        if ($studentRecord->studentClass) {
                            $className = $studentRecord->studentClass->class_name ?? 'N/A';
                        }
                    }
                } elseif ($attempt->student && $attempt->student->user_id == $userId) {
                    // THIRD: Fallback to attempt relationship (only if user_id matches)
                    $studentRecord = $attempt->student;
                    if ($attempt->student->user) {
                        $studentName = $attempt->student->user->first_name . ' ' . $attempt->student->user->last_name;
                    }
                    if ($attempt->student->studentClass) {
                        $className = $attempt->student->studentClass->class_name ?? 'N/A';
                    }
                }
                
                $studentGrades[$userId] = [
                    'student' => $studentRecord,
                    'student_name' => $studentName,
                    'class_name' => $className,
                    'grades' => [],
                    'total_grades' => 0,
                    'average_percentage' => 0,
                ];
            }
            
            if ($userId && isset($studentGrades[$userId])) {
                // CRITICAL FIX: Update student name from $studentData if available (more reliable)
                if (isset($studentData[$userId]) && !empty($studentData[$userId]['student_name'])) {
                    $studentGrades[$userId]['student_name'] = $studentData[$userId]['student_name'];
                    $studentGrades[$userId]['class_name'] = $studentData[$userId]['class_name'];
                    if (!empty($studentData[$userId]['student'])) {
                        $studentGrades[$userId]['student'] = $studentData[$userId]['student'];
                    }
                }
                
                // Check if there's a Grade record for this quiz attempt
                $grade = $quizAttemptGrades->get($attempt->attempt_id);
                
                // If Grade record exists, use it (it has proper grade_value and max_grade)
                if ($grade) {
                    // CRITICAL FIX: Always use userId from the attempt, not from the grade record
                    // The grade record might have a different student_id if there was data inconsistency
                    // But the attempt's student_id is the source of truth for which student took the quiz
                    // Only use gradeUserId if it matches the attempt's userId (safety check)
                    $gradeUserId = $studentIdToUserIdMap[$grade->student_id] ?? null;
                    if (!$gradeUserId && $grade->student && $grade->student->user_id) {
                        $gradeUserId = $grade->student->user_id;
                        if (!isset($studentIdToUserIdMap[$grade->student_id])) {
                            $studentIdToUserIdMap[$grade->student_id] = $gradeUserId;
                        }
                    }
                    
                    // CRITICAL FIX: Only use gradeUserId if it matches userId from attempt
                    // This prevents grades from being assigned to the wrong user
                    if ($gradeUserId && $gradeUserId == $userId) {
                        $finalUserId = $gradeUserId;
                    } else {
                        // Use userId from attempt (source of truth)
                        $finalUserId = $userId;
                    }
                    
                    // DEBUG: Log if there's a mismatch
                    if ($gradeUserId && $gradeUserId != $userId) {
                        \Log::warning('TeacherGradeController: Grade record student_id mismatch with attempt', [
                            'selectedClassId' => $selectedClassId,
                            'attempt_id' => $attempt->attempt_id,
                            'attempt_student_id' => $attempt->student_id,
                            'attempt_userId' => $userId,
                            'grade_student_id' => $grade->student_id,
                            'grade_userId' => $gradeUserId,
                            'using_userId' => $finalUserId
                        ]);
                    }
                    
                    if ($finalUserId && isset($studentGrades[$finalUserId])) {
                        $gradeValue = $grade->grade_value ?? 0;
                        $maxGrade = $grade->max_grade ?? 100;
                        $percentage = $grade->percentage ?? ($maxGrade > 0 ? ($gradeValue / $maxGrade) * 100 : 0);
                        $percentage = min(100, max(0, $percentage));
                        
                        // DEBUG: Check for duplicate grades before adding
                        $isDuplicate = false;
                        foreach ($studentGrades[$finalUserId]['grades'] ?? [] as $existingGrade) {
                            if (isset($existingGrade->quiz_attempt_id) && $existingGrade->quiz_attempt_id == $attempt->attempt_id) {
                                $isDuplicate = true;
                                \Log::warning('TeacherGradeController: Duplicate quiz grade detected', [
                                    'selectedClassId' => $selectedClassId,
                                    'userId' => $finalUserId,
                                    'attempt_id' => $attempt->attempt_id,
                                    'student_id' => $attempt->student_id
                                ]);
                                break;
                            }
                        }
                        
                        if (!$isDuplicate) {
                            $studentGrades[$finalUserId]['grades'][] = (object)[
                            'type' => 'quiz',
                            'grade_value' => $gradeValue,
                            'max_grade' => $maxGrade,
                            'percentage' => round($percentage, 2),
                            'feedback' => $grade->feedback ?? null,
                            'graded_at' => $grade->graded_at ?? $attempt->submitted_at,
                            'item_name' => $attempt->quiz ? $attempt->quiz->title : 'N/A',
                            'assignment_submission_id' => null,
                            'quiz_attempt_id' => $attempt->attempt_id,
                            ];
                            $studentGrades[$finalUserId]['total_grades']++;
                            
                            // DEBUG: Log immediately after adding grade
                            \Log::debug('TeacherGradeController: Quiz grade ADDED to array (with Grade record)', [
                                'selectedClassId' => $selectedClassId,
                                'userId' => $finalUserId,
                                'attempt_student_id' => $attempt->student_id,
                                'grade_student_id' => $grade->student_id,
                                'attempt_id' => $attempt->attempt_id,
                                'total_grades_now' => $studentGrades[$finalUserId]['total_grades'],
                                'grades_count_now' => count($studentGrades[$finalUserId]['grades']),
                                'student_name' => $studentGrades[$finalUserId]['student_name'] ?? 'N/A'
                            ]);
                        }
                    }
                } else {
                    // Recalculate score from actual student answers for accuracy
                    // This ensures we always get the correct score regardless of what's stored in quiz_attempts.score
                    $totalQuestions = 0;
                    $correctAnswers = 0;
                    
                    // Get total number of questions from the quiz
                    if ($attempt->quiz && $attempt->quiz->questions) {
                        $totalQuestions = $attempt->quiz->questions->count();
                    }
                    
                    // Count correct answers from StudentAnswer records
                    if ($attempt->answers && $attempt->answers->count() > 0) {
                        $correctAnswers = $attempt->answers->where('is_correct', true)->count();
                    }
                    
                    // Calculate accurate score and percentage
                    if ($totalQuestions > 0) {
                        // Score is the number of correct answers (points)
                        $score = $correctAnswers;
                        $maxScore = $totalQuestions;
                        // Percentage is (correct / total) * 100
                        $percentage = ($correctAnswers / $totalQuestions) * 100;
                    } else {
                        // Fallback: use stored score if we can't count questions
                        // Note: According to QuizController line 336, score is stored as a percentage (0-100)
                        // So if we have stored score, it's already a percentage
                        $storedScore = $attempt->score ?? 0;
                        
                        // If stored score is 0-100, it's likely a percentage
                        // Otherwise, try to use max_score
                        if ($storedScore >= 0 && $storedScore <= 100) {
                            // Stored score is a percentage
                            $percentage = $storedScore;
                            $maxScore = $attempt->quiz->max_score ?? 100;
                            // Convert percentage to points for display
                            $score = ($percentage / 100) * $maxScore;
                        } else {
                            // Treat as points
                            $maxScore = $attempt->quiz->max_score ?? 100;
                            $score = $storedScore;
                            $percentage = $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
                        }
                    }
                    
                    // Ensure percentage is within valid range
                    $percentage = min(100, max(0, round($percentage, 2)));
                    $score = round($score, 2);
                    
                    // DEBUG: Check for duplicate grades before adding
                    $isDuplicate = false;
                    foreach ($studentGrades[$userId]['grades'] ?? [] as $existingGrade) {
                        if (isset($existingGrade->quiz_attempt_id) && $existingGrade->quiz_attempt_id == $attempt->attempt_id) {
                            $isDuplicate = true;
                            \Log::warning('TeacherGradeController: Duplicate quiz grade detected (no grade record)', [
                                'selectedClassId' => $selectedClassId,
                                'userId' => $userId,
                                'attempt_id' => $attempt->attempt_id,
                                'student_id' => $attempt->student_id
                            ]);
                            break;
                        }
                    }
                    
                    if (!$isDuplicate) {
                        $studentGrades[$userId]['grades'][] = (object)[
                            'type' => 'quiz',
                            'grade_value' => $score,
                            'max_grade' => $maxScore,
                            'percentage' => $percentage,
                            'feedback' => null,
                            'graded_at' => $attempt->submitted_at,
                            'item_name' => $attempt->quiz ? $attempt->quiz->title : 'N/A',
                            'assignment_submission_id' => null,
                            'quiz_attempt_id' => $attempt->attempt_id,
                        ];
                        $studentGrades[$userId]['total_grades']++;
                        
                        // DEBUG: Log immediately after adding grade
                        \Log::debug('TeacherGradeController: Quiz grade ADDED to array (no Grade record)', [
                            'selectedClassId' => $selectedClassId,
                            'userId' => $userId,
                            'student_id' => $attempt->student_id,
                            'attempt_id' => $attempt->attempt_id,
                            'total_grades_now' => $studentGrades[$userId]['total_grades'],
                            'grades_count_now' => count($studentGrades[$userId]['grades']),
                            'student_name' => $studentGrades[$userId]['student_name'] ?? 'N/A'
                        ]);
                    }
                }
            } else {
                // DEBUG: Log when quiz attempt is NOT added (no userId or no studentGrades entry)
                \Log::warning('TeacherGradeController: Quiz attempt NOT added', [
                    'student_id' => $attempt->student_id,
                    'userId' => $userId,
                    'hasUserId' => !empty($userId),
                    'hasStudentGradesEntry' => isset($studentGrades[$userId ?? 'null']),
                    'selectedClassId' => $selectedClassId
                ]);
            }
        }
        
        // Add game progress
        foreach ($gameProgresses as $progress) {
            // Try to get user_id from the map first
            $userId = $studentIdToUserIdMap[$progress->student_id] ?? null;
            
            // Fallback: if mapping fails, try to get user_id from the progress's student relationship
            if (!$userId && $progress->student && $progress->student->user_id) {
                $userId = $progress->student->user_id;
                // Also add to map for future lookups
                if (!isset($studentIdToUserIdMap[$progress->student_id])) {
                    $studentIdToUserIdMap[$progress->student_id] = $userId;
                }
            }
            
            // DEBUG: Log game progress assignment
            \Log::debug('TeacherGradeController: Assigning game progress', [
                'selectedClassId' => $selectedClassId,
                'progress_id' => $progress->id ?? 'N/A',
                'student_id' => $progress->student_id,
                'mapped_userId' => $userId,
                'progress_student_user_id' => $progress->student->user_id ?? null,
                'progress_student_name' => $progress->student->user->first_name . ' ' . $progress->student->user->last_name ?? 'N/A'
            ]);
            
            // CRITICAL FIX: If userId exists but studentGrades entry doesn't, initialize it
            // IMPORTANT: Always prefer $studentData as source of truth for student name
            if ($userId && !isset($studentGrades[$userId])) {
                // Initialize on the fly - prefer studentData, then allStudentRecords, then progress relationship
                $studentRecord = null;
                $studentName = 'Unknown Student';
                $className = 'N/A';
                
                // FIRST: Check if we have this user in studentData (most reliable source)
                if (isset($studentData[$userId])) {
                    $studentName = $studentData[$userId]['student_name'];
                    $className = $studentData[$userId]['class_name'];
                    $studentRecord = $studentData[$userId]['student'] ?? null;
                } elseif (!empty($allStudentRecords)) {
                    // SECOND: Get from allStudentRecords (query by user_id to get correct record)
                    $studentRecord = $allStudentRecords->firstWhere('user_id', $userId);
                    if ($studentRecord && $studentRecord->user) {
                        $studentName = $studentRecord->user->first_name . ' ' . $studentRecord->user->last_name;
                        if ($studentRecord->studentClass) {
                            $className = $studentRecord->studentClass->class_name ?? 'N/A';
                        }
                    }
                } elseif ($progress->student && $progress->student->user_id == $userId) {
                    // THIRD: Fallback to progress relationship (only if user_id matches)
                    $studentRecord = $progress->student;
                    if ($progress->student->user) {
                        $studentName = $progress->student->user->first_name . ' ' . $progress->student->user->last_name;
                    }
                    if ($progress->student->studentClass) {
                        $className = $progress->student->studentClass->class_name ?? 'N/A';
                    }
                }
                
                $studentGrades[$userId] = [
                    'student' => $studentRecord,
                    'student_name' => $studentName,
                    'class_name' => $className,
                    'grades' => [],
                    'total_grades' => 0,
                    'average_percentage' => 0,
                ];
            }
            
            if ($userId && isset($studentGrades[$userId])) {
                // CRITICAL FIX: Update student name from $studentData if available (more reliable)
                if (isset($studentData[$userId]) && !empty($studentData[$userId]['student_name'])) {
                    $studentGrades[$userId]['student_name'] = $studentData[$userId]['student_name'];
                    $studentGrades[$userId]['class_name'] = $studentData[$userId]['class_name'];
                    if (!empty($studentData[$userId]['student'])) {
                        $studentGrades[$userId]['student'] = $studentData[$userId]['student'];
                    }
                }
                
                $gameName = 'Game';
                if ($progress->game) {
                    if ($progress->game->lesson && $progress->game->lesson->title) {
                        $gameName = $progress->game->lesson->title . ' - Game';
                    } else {
                        $gameName = 'Game #' . $progress->game_id;
                    }
                }
                
                // For games, we'll use the score as both grade_value and max_grade for display
                // Since games don't have a max_score, we'll show the score as a percentage
                $score = $progress->score ?? 0;
                
                // DEBUG: Check for duplicate grades before adding
                // For games, check by game_id and completed_at to avoid duplicates
                $isDuplicate = false;
                foreach ($studentGrades[$userId]['grades'] ?? [] as $existingGrade) {
                    if ($existingGrade->type == 'game' && 
                        isset($progress->game_id) && 
                        isset($existingGrade->game_id) && 
                        $existingGrade->game_id == $progress->game_id &&
                        isset($existingGrade->graded_at) &&
                        $existingGrade->graded_at == $progress->completed_at) {
                        $isDuplicate = true;
                        \Log::warning('TeacherGradeController: Duplicate game progress detected', [
                            'selectedClassId' => $selectedClassId,
                            'userId' => $userId,
                            'game_id' => $progress->game_id,
                            'student_id' => $progress->student_id
                        ]);
                        break;
                    }
                }
                
                if (!$isDuplicate) {
                    $gameGrade = (object)[
                        'type' => 'game',
                        'grade_value' => $score,
                        'max_grade' => 100, // Display as percentage
                        'percentage' => min(100, max(0, $score)), // Ensure percentage is between 0-100
                        'feedback' => null,
                        'graded_at' => $progress->completed_at,
                        'item_name' => $gameName,
                        'assignment_submission_id' => null,
                        'quiz_attempt_id' => null,
                        'game_id' => $progress->game_id ?? null, // Add game_id for duplicate detection
                    ];
                    $studentGrades[$userId]['grades'][] = $gameGrade;
                    $studentGrades[$userId]['total_grades']++;
                    
                    // DEBUG: Log immediately after adding grade
                    \Log::debug('TeacherGradeController: Game progress ADDED to array', [
                        'selectedClassId' => $selectedClassId,
                        'userId' => $userId,
                        'student_id' => $progress->student_id,
                        'game_id' => $progress->game_id ?? 'N/A',
                        'total_grades_now' => $studentGrades[$userId]['total_grades'],
                        'grades_count_now' => count($studentGrades[$userId]['grades']),
                        'student_name' => $studentGrades[$userId]['student_name'] ?? 'N/A'
                    ]);
                }
            } else {
                // DEBUG: Log when game progress is NOT added
                \Log::warning('TeacherGradeController: Game progress NOT added', [
                    'student_id' => $progress->student_id,
                    'userId' => $userId,
                    'hasUserId' => !empty($userId),
                    'hasStudentGradesEntry' => isset($studentGrades[$userId ?? 'null']),
                    'selectedClassId' => $selectedClassId
                ]);
            }
        }
        
        // DEBUG: Log studentIdToUserIdMap to verify mappings
        \Log::info('TeacherGradeController: studentIdToUserIdMap', [
            'selectedClassId' => $selectedClassId,
            'map_count' => count($studentIdToUserIdMap),
            'map_sample' => array_slice($studentIdToUserIdMap, 0, 10, true)
        ]);
        
        // Sort grades by graded_at date (most recent first) for each student
        // CRITICAL FIX: Use array keys to avoid reference issues
        foreach (array_keys($studentGrades) as $userId) {
            if (isset($studentGrades[$userId]['grades']) && is_array($studentGrades[$userId]['grades'])) {
                usort($studentGrades[$userId]['grades'], function($a, $b) {
                    $dateA = $a->graded_at ? (is_string($a->graded_at) ? strtotime($a->graded_at) : $a->graded_at->timestamp) : 0;
                    $dateB = $b->graded_at ? (is_string($b->graded_at) ? strtotime($b->graded_at) : $b->graded_at->timestamp) : 0;
                    return $dateB - $dateA;
                });
            }
        }
        
        // Calculate average percentage for each student
        // Only include assignments and quizzes in the average (exclude games as they use different scoring)
        $overallAverage = 0;
        $studentsWithGrades = 0;
        
        // CRITICAL FIX: Use array keys to avoid reference issues
        foreach (array_keys($studentGrades) as $userId) {
            $totalPercentage = 0;
            $countForAverage = 0;
            
            if (isset($studentGrades[$userId]['grades']) && is_array($studentGrades[$userId]['grades'])) {
                foreach ($studentGrades[$userId]['grades'] as $grade) {
                    // Only calculate average from assignments and quizzes (exclude games)
                    if (isset($grade->type) && $grade->type !== 'game') {
                        $percentage = $grade->percentage ?? 0;
                        // Ensure percentage is reasonable (0-100)
                        if ($percentage >= 0 && $percentage <= 100) {
                            $totalPercentage += $percentage;
                            $countForAverage++;
                        }
                    }
                }
            }
            
            if ($countForAverage > 0) {
                $studentGrades[$userId]['average_percentage'] = round($totalPercentage / $countForAverage, 2);
                // Add to overall average calculation
                $overallAverage += $studentGrades[$userId]['average_percentage'];
                $studentsWithGrades++;
            } else {
                // If no assignments/quizzes, set average to 0
                $studentGrades[$userId]['average_percentage'] = 0;
            }
        }
        
        // Calculate overall average grade across all students
        $overallAverageGrade = $studentsWithGrades > 0 ? round($overallAverage / $studentsWithGrades, 1) : 0;
        
        // DEBUG: Log studentGrades AFTER sorting and average calculation (to verify grades still exist)
        \Log::debug('TeacherGradeController: studentGrades AFTER sorting and average calculation', [
            'selectedClassId' => $selectedClassId,
            'total_entries' => count($studentGrades),
            'entries' => array_map(function($data, $userId) {
                return [
                    'userId' => $userId,
                    'student_name' => $data['student_name'] ?? 'N/A',
                    'grades_count' => count($data['grades'] ?? []),
                    'total_grades' => $data['total_grades'] ?? 0,
                    'average_percentage' => $data['average_percentage'] ?? 0,
                    'grades_is_array' => is_array($data['grades'] ?? null),
                    'grades_not_empty' => !empty($data['grades'] ?? [])
                ];
            }, $studentGrades, array_keys($studentGrades))
        ]);
        
        // DEBUG: Log studentGrades IMMEDIATELY before building details (to catch any issues)
        \Log::debug('TeacherGradeController: studentGrades IMMEDIATELY before building details', [
            'selectedClassId' => $selectedClassId,
            'total_entries' => count($studentGrades),
            'entries' => array_map(function($data, $userId) {
                return [
                    'userId' => $userId,
                    'student_name' => $data['student_name'] ?? 'N/A',
                    'grades_count' => count($data['grades'] ?? []),
                    'total_grades' => $data['total_grades'] ?? 0,
                    'grades_array_type' => gettype($data['grades'] ?? null),
                    'grades_is_array' => is_array($data['grades'] ?? null)
                ];
            }, $studentGrades, array_keys($studentGrades))
        ]);
        
        // DEBUG: Log studentGrades after processing all grades - DETAILED
        $studentGradesDetails = [];
        foreach ($studentGrades as $userId => $data) {
            // Track which student_ids contributed to these grades
            $gradeStudentIds = [];
            foreach ($data['grades'] ?? [] as $grade) {
                // Try to find which student_id this grade came from
                if (isset($grade->quiz_attempt_id)) {
                    $attempt = $quizAttempts->firstWhere('attempt_id', $grade->quiz_attempt_id);
                    if ($attempt) {
                        $gradeStudentIds[] = $attempt->student_id;
                    }
                } elseif (isset($grade->assignment_submission_id)) {
                    $assignmentGrade = $assignmentGrades->firstWhere('assignment_submission_id', $grade->assignment_submission_id);
                    if ($assignmentGrade) {
                        $gradeStudentIds[] = $assignmentGrade->student_id;
                    }
                }
            }
            
            $studentGradesDetails[$userId] = [
                'student_name' => $data['student_name'] ?? 'N/A',
                'total_grades' => $data['total_grades'] ?? 0,
                'average_percentage' => $data['average_percentage'] ?? 0,
                'grades_count' => count($data['grades'] ?? []),
                'grades_types' => array_map(function($g) { return $g->type ?? 'unknown'; }, $data['grades'] ?? []),
                'in_studentData' => isset($studentData[$userId]),
                'grade_student_ids' => array_unique($gradeStudentIds),
                'expected_student_ids' => array_keys(array_filter($studentIdToUserIdMap, function($uid) use ($userId) { return $uid == $userId; }))
            ];
        }
        
        \Log::info('TeacherGradeController: StudentGrades after processing - DETAILED', [
            'selectedClassId' => $selectedClassId,
            'isAllClasses' => empty($selectedClassId),
            'totalStudentsInGrades' => count($studentGrades),
            'studentsWithGrades' => $studentsWithGrades,
            'overallAverageGrade' => $overallAverageGrade,
            'studentGrades' => $studentGradesDetails,
            'comparison_note' => 'This shows what grades exist in $studentGrades. Compare with Final studentGradesByClass to see if they match.'
        ]);
        
        // Filter students by search query if provided (accurate search by first name, last name, or full name)
        if (!empty($searchQuery)) {
            $searchQuery = trim($searchQuery);
            $searchLower = strtolower($searchQuery);
            $filteredStudentGrades = [];
            
            foreach ($studentGrades as $userId => $data) {
                // Get student data for accurate search
                $studentInfo = $studentData[$userId] ?? null;
                if ($studentInfo) {
                    $firstName = strtolower($studentInfo['first_name'] ?? '');
                    $lastName = strtolower($studentInfo['last_name'] ?? '');
                    $fullName = strtolower($data['student_name']);
                    
                    // Accurate search: matches first name, last name, or full name (case-insensitive)
                    $matches = false;
                    
                    // Check if search matches first name
                    if (strpos($firstName, $searchLower) !== false) {
                        $matches = true;
                    }
                    // Check if search matches last name
                    elseif (strpos($lastName, $searchLower) !== false) {
                        $matches = true;
                    }
                    // Check if search matches full name
                    elseif (strpos($fullName, $searchLower) !== false) {
                        $matches = true;
                    }
                    
                    if ($matches) {
                        $filteredStudentGrades[$userId] = $data;
                    }
                }
            }
            
            $studentGrades = $filteredStudentGrades;
        }
        
        // DEBUG: Log studentGrades BEFORE deduplication
        \Log::info('TeacherGradeController: studentGrades BEFORE deduplication', [
            'selectedClassId' => $selectedClassId,
            'count' => count($studentGrades),
            'data' => array_map(function($data, $userId) {
                return [
                    'userId' => $userId,
                    'student_name' => $data['student_name'] ?? 'N/A',
                    'grades_count' => count($data['grades'] ?? []),
                    'total_grades' => $data['total_grades'] ?? 0
                ];
            }, $studentGrades, array_keys($studentGrades))
        ]);
        
        // Final deduplication: ensure no duplicates by user_id
        // CRITICAL FIX: Only deduplicate by user_id, NOT by student name
        // Multiple users can have the same name, so we should only check user_id
        // If the same user_id appears multiple times, keep the one with the most grades
        $uniqueStudentGrades = [];
        
        foreach ($studentGrades as $userId => $data) {
            // If this userId already exists, keep the one with more grades
            if (isset($uniqueStudentGrades[$userId])) {
                $existingGradesCount = count($uniqueStudentGrades[$userId]['grades'] ?? []);
                $newGradesCount = count($data['grades'] ?? []);
                
                // Keep the entry with more grades (or the new one if equal)
                if ($newGradesCount > $existingGradesCount) {
                    $uniqueStudentGrades[$userId] = $data;
                }
                // If equal, merge the grades arrays (in case they're different)
                elseif ($newGradesCount == $existingGradesCount && $newGradesCount > 0) {
                    // Merge grades, avoiding duplicates
                    $existingGradeIds = [];
                    foreach ($uniqueStudentGrades[$userId]['grades'] ?? [] as $grade) {
                        $gradeId = $grade->quiz_attempt_id ?? $grade->assignment_submission_id ?? null;
                        if ($gradeId) {
                            $existingGradeIds[] = $gradeId;
                        }
                    }
                    
                    foreach ($data['grades'] ?? [] as $grade) {
                        $gradeId = $grade->quiz_attempt_id ?? $grade->assignment_submission_id ?? null;
                        if ($gradeId && !in_array($gradeId, $existingGradeIds)) {
                            $uniqueStudentGrades[$userId]['grades'][] = $grade;
                            $uniqueStudentGrades[$userId]['total_grades']++;
                        }
                    }
                    
                    // Recalculate average
                    $totalPercentage = 0;
                    $countForAverage = 0;
                    foreach ($uniqueStudentGrades[$userId]['grades'] as $grade) {
                        if (isset($grade->type) && $grade->type !== 'game') {
                            $percentage = $grade->percentage ?? 0;
                            if ($percentage >= 0 && $percentage <= 100) {
                                $totalPercentage += $percentage;
                                $countForAverage++;
                            }
                        }
                    }
                    if ($countForAverage > 0) {
                        $uniqueStudentGrades[$userId]['average_percentage'] = round($totalPercentage / $countForAverage, 2);
                    }
                }
            } else {
                // First time seeing this userId, add it
                $uniqueStudentGrades[$userId] = $data;
            }
        }
        
        // DEBUG: Log deduplication results
        \Log::info('TeacherGradeController: After deduplication', [
            'selectedClassId' => $selectedClassId,
            'before_count' => count($studentGrades),
            'after_count' => count($uniqueStudentGrades),
            'uniqueStudentGrades' => array_map(function($data) {
                return [
                    'student_name' => $data['student_name'] ?? 'N/A',
                    'total_grades' => $data['total_grades'] ?? 0,
                    'grades_count' => count($data['grades'] ?? [])
                ];
            }, $uniqueStudentGrades)
        ]);
        
        // Ensure array is properly keyed by user_id (remove any numeric keys that might have been created)
        $finalStudentGrades = [];
        foreach ($uniqueStudentGrades as $userId => $data) {
            $finalStudentGrades[$userId] = $data;
        }
        $studentGrades = $finalStudentGrades;
        
        // Group students by class for display
        // IMPORTANT: Include ALL students from ALL classes, even if they have no grades
        $studentGradesByClass = [];
        
        // First, initialize all classes that have students (from userClassMap)
        // CRITICAL FIX: Only process users that are in $userIds (filtered users)
        // This ensures we don't show classes for users that aren't in the current filter
        // Convert $userIds to array for in_array check
        $userIdsArray = is_array($userIds) ? $userIds : array_values($userIds);
        foreach ($userClassMap as $userId => $classIds) {
            // Only process if this user is in the filtered userIds
            if (!in_array($userId, $userIdsArray)) {
                continue;
            }
            foreach ($classIds as $classId) {
                if (!isset($studentGradesByClass[$classId])) {
                    $class = $allClasses->firstWhere('class_id', $classId);
                    if ($class) {
                        $studentGradesByClass[$classId] = [
                            'class_id' => $classId,
                            'class_name' => $class->class_name,
                            'students' => [],
                        ];
                    }
                }
            }
        }
        
        // Then, add students to their respective classes
        // CRITICAL FIX: Include ALL students from both studentData AND studentGrades
        // This ensures students with grades are included even if they're not in studentData
        // CRITICAL FIX: If search query is provided, only include matching students
        // First, process students from studentData
        foreach ($studentData as $userId => $studentInfo) {
            // If search query exists, only process students that match the search
            if (!empty($searchQuery)) {
                // Only include this student if they're in the filtered $studentGrades
                if (!isset($studentGrades[$userId])) {
                    continue; // Skip this student - they don't match the search
                }
            }
            // Get student grades data if available, otherwise create empty structure
            $hasGradesInStudentGrades = isset($studentGrades[$userId]);
            $gradesCount = $hasGradesInStudentGrades ? count($studentGrades[$userId]['grades'] ?? []) : 0;
            
            // DEBUG: Log when processing studentData for studentGradesByClass
            if ($hasGradesInStudentGrades && $gradesCount > 0) {
                \Log::info('TeacherGradeController: Processing student from studentData WITH grades', [
                    'selectedClassId' => $selectedClassId,
                    'userId' => $userId,
                    'student_name' => $studentInfo['student_name'] ?? 'N/A',
                    'grades_count' => $gradesCount,
                    'average_percentage' => $studentGrades[$userId]['average_percentage'] ?? 0
                ]);
            }
            
            // CRITICAL FIX: Always use $studentData as source of truth for student name
            // Merge grades from $studentGrades but keep name/class from $studentData
            if (isset($studentGrades[$userId])) {
                $studentGradeData = $studentGrades[$userId];
                // Override name and class from $studentData (more reliable)
                $studentGradeData['student_name'] = $studentInfo['student_name'];
                $studentGradeData['class_name'] = $studentInfo['class_name'];
                if (!empty($studentInfo['student'])) {
                    $studentGradeData['student'] = $studentInfo['student'];
                }
            } else {
                $studentGradeData = [
                    'student' => $studentInfo['student'] ?? null,
                    'student_name' => $studentInfo['student_name'],
                    'class_name' => $studentInfo['class_name'],
                    'grades' => [],
                    'total_grades' => 0,
                    'average_percentage' => 0,
                ];
            }
            
            // Get classes from studentInfo or userClassMap
            $studentClasses = $studentInfo['classes'] ?? [];
            if (empty($studentClasses) && isset($userClassMap[$userId])) {
                // Build classes array from userClassMap
                foreach ($userClassMap[$userId] as $classId) {
                    $class = $allClasses->firstWhere('class_id', $classId);
                    if ($class) {
                        $studentClasses[] = [
                            'class_id' => $class->class_id,
                            'class_name' => $class->class_name,
                        ];
                    }
                }
            }
            
            // Add student to all their classes
            foreach ($studentClasses as $classInfo) {
                $classId = $classInfo['class_id'];
                // Ensure class exists in the array
                if (!isset($studentGradesByClass[$classId])) {
                    $class = $allClasses->firstWhere('class_id', $classId);
                    $studentGradesByClass[$classId] = [
                        'class_id' => $classId,
                        'class_name' => $class ? $class->class_name : 'Unknown Class',
                        'students' => [],
                    ];
                }
                // Only add if not already in this class's list
                if (!isset($studentGradesByClass[$classId]['students'][$userId])) {
                    $studentGradesByClass[$classId]['students'][$userId] = $studentGradeData;
                    
                    // DEBUG: Log when student is added to studentGradesByClass
                    $gradesCount = count($studentGradeData['grades'] ?? []);
                    if ($gradesCount > 0) {
                        \Log::info('TeacherGradeController: Student ADDED to studentGradesByClass WITH grades', [
                            'selectedClassId' => $selectedClassId,
                            'classId' => $classId,
                            'class_name' => $classInfo['class_name'],
                            'userId' => $userId,
                            'student_name' => $studentGradeData['student_name'] ?? 'N/A',
                            'grades_count' => $gradesCount,
                            'average_percentage' => $studentGradeData['average_percentage'] ?? 0
                        ]);
                    } else {
                        \Log::debug('TeacherGradeController: Student ADDED to studentGradesByClass WITHOUT grades', [
                            'selectedClassId' => $selectedClassId,
                            'classId' => $classId,
                            'class_name' => $classInfo['class_name'],
                            'userId' => $userId,
                            'student_name' => $studentGradeData['student_name'] ?? 'N/A'
                        ]);
                    }
                }
            }
        }
        
        // CRITICAL FIX: Also process students from studentGrades that might not be in studentData
        // This ensures students with grades are displayed even if they weren't in studentData
        foreach ($studentGrades as $userId => $gradeData) {
            // Skip if already processed from studentData
            if (isset($studentData[$userId])) {
                continue;
            }
            
            // CRITICAL FIX: Only process if this user is in the filtered userIds
            // This ensures we don't show students that aren't in the current filter
            if (!in_array($userId, $userIdsArray)) {
                // DEBUG: Log when student with grades is skipped because not in filtered userIds
                \Log::warning('TeacherGradeController: Student with grades skipped (not in filtered userIds)', [
                    'selectedClassId' => $selectedClassId,
                    'userId' => $userId,
                    'student_name' => $gradeData['student_name'] ?? 'N/A',
                    'grades_count' => count($gradeData['grades'] ?? []),
                    'in_userIdsArray' => in_array($userId, $userIdsArray),
                    'userIdsArray_sample' => array_slice($userIdsArray, 0, 5)
                ]);
                continue;
            }
            
            // DEBUG: Log when processing student from studentGrades (not in studentData)
            if (count($gradeData['grades'] ?? []) > 0) {
                \Log::info('TeacherGradeController: Processing student from studentGrades (not in studentData) WITH grades', [
                    'selectedClassId' => $selectedClassId,
                    'userId' => $userId,
                    'student_name' => $gradeData['student_name'] ?? 'N/A',
                    'grades_count' => count($gradeData['grades'] ?? []),
                    'average_percentage' => $gradeData['average_percentage'] ?? 0
                ]);
            }
            
            // Get classes from userClassMap
            $studentClasses = [];
            if (isset($userClassMap[$userId])) {
                foreach ($userClassMap[$userId] as $classId) {
                    $class = $allClasses->firstWhere('class_id', $classId);
                    if ($class) {
                        $studentClasses[] = [
                            'class_id' => $class->class_id,
                            'class_name' => $class->class_name,
                        ];
                    }
                }
            }
            
            // Add student to all their classes
            foreach ($studentClasses as $classInfo) {
                $classId = $classInfo['class_id'];
                // Ensure class exists in the array
                if (!isset($studentGradesByClass[$classId])) {
                    $class = $allClasses->firstWhere('class_id', $classId);
                    $studentGradesByClass[$classId] = [
                        'class_id' => $classId,
                        'class_name' => $class ? $class->class_name : 'Unknown Class',
                        'students' => [],
                    ];
                }
                // Only add if not already in this class's list
                if (!isset($studentGradesByClass[$classId]['students'][$userId])) {
                    $studentGradesByClass[$classId]['students'][$userId] = $gradeData;
                    
                    // DEBUG: Log when student is added to studentGradesByClass from studentGrades
                    $gradesCount = count($gradeData['grades'] ?? []);
                    if ($gradesCount > 0) {
                        \Log::info('TeacherGradeController: Student ADDED to studentGradesByClass from studentGrades WITH grades', [
                            'selectedClassId' => $selectedClassId,
                            'classId' => $classId,
                            'class_name' => $classInfo['class_name'],
                            'userId' => $userId,
                            'student_name' => $gradeData['student_name'] ?? 'N/A',
                            'grades_count' => $gradesCount,
                            'average_percentage' => $gradeData['average_percentage'] ?? 0
                        ]);
                    }
                }
            }
        }
        
        // Remove empty classes (classes with no students)
        // CRITICAL FIX: Remove empty classes when:
        // 1. No class filter is selected (show all classes, but only those with students)
        // 2. A search query is provided (only show classes with matching students)
        if (empty($selectedClassId) || !empty($searchQuery)) {
            foreach ($studentGradesByClass as $classId => $classData) {
                if (empty($classData['students'])) {
                    unset($studentGradesByClass[$classId]);
                }
            }
        }
        
        // Sort classes by name
        uasort($studentGradesByClass, function($a, $b) {
            return strcmp($a['class_name'], $b['class_name']);
        });
        
        // DEBUG: Log final studentGradesByClass structure with detailed comparison
        $detailedClasses = [];
        foreach ($studentGradesByClass as $classId => $classData) {
            $studentsDetails = [];
            foreach ($classData['students'] ?? [] as $userId => $studentData) {
                $studentsDetails[$userId] = [
                    'student_name' => $studentData['student_name'] ?? 'N/A',
                    'total_grades' => $studentData['total_grades'] ?? 0,
                    'average_percentage' => $studentData['average_percentage'] ?? 0,
                    'grades_count' => count($studentData['grades'] ?? []),
                    'grades_types' => array_map(function($g) { return $g->type ?? 'unknown'; }, $studentData['grades'] ?? [])
                ];
            }
            $detailedClasses[$classId] = [
                'class_id' => $classData['class_id'],
                'class_name' => $classData['class_name'],
                'students_count' => count($classData['students'] ?? []),
                'students' => $studentsDetails
            ];
        }
        
        \Log::info('TeacherGradeController: Final studentGradesByClass - DETAILED', [
            'selectedClassId' => $selectedClassId,
            'isAllClasses' => empty($selectedClassId),
            'totalClasses' => count($studentGradesByClass),
            'classes' => $detailedClasses,
            'comparison_note' => 'Compare this with the same log when selectedClassId is different to see the difference'
        ]);
        
        return view('teacher.grades', compact(
            'studentGrades', 
            'classes', 
            'allClasses',
            'overallAverageGrade', 
            'studentsWithGrades', 
            'searchQuery',
            'selectedClassId',
            'studentGradesByClass'
        ));
    }
}
