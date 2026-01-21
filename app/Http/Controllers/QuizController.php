<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\Level;
use App\Models\StudentClass;
use App\Models\QuizAttempt;
use App\Models\StudentAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    // Teacher: Show quiz creation page
    public function create()
    {
        $levels = Level::all();
        $classes = StudentClass::where('teacher_id', Auth::id())->get();
        return view('quizzes.create', compact('levels', 'classes'));
    }

    // Teacher: Store new quiz
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'level_id' => 'required|exists:levels,level_id',
            'class_id' => 'required|exists:student_classes,class_id',
            'timer_minutes' => 'required|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.image' => 'nullable|image|max:2048',
            'questions.*.background_color' => 'nullable|string|size:7',
            'questions.*.choices' => 'required|array|min:2',
            'questions.*.choices.*' => 'required|string',
            'questions.*.correct_answer' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            // Verify the class belongs to the teacher
            $classId = $request->class_id;
            $teacherClass = StudentClass::where('class_id', $classId)
                ->where('teacher_id', Auth::id())
                ->first();
            
            if (!$teacherClass) {
                throw new \Exception('You do not have permission to create quizzes for this class.');
            }

            $quiz = Quiz::create([
                'teacher_id' => Auth::id(),
                'level_id' => $request->level_id,
                'class_id' => $classId,
                'title' => $request->title,
                'description' => $request->description ?? null,
                'timer_minutes' => $request->timer_minutes,
                'due_date' => now()->addDays(30),
                'max_score' => count($request->questions),
                'passing_score' => ceil(count($request->questions) * 0.6),
                'is_active' => true,
            ]);

            foreach ($request->questions as $index => $questionData) {
                $imagePath = null;
                if (isset($questionData['image']) && $questionData['image']) {
                    $imagePath = $questionData['image']->store('quiz-questions', 'public');
                }

                // Filter out empty choices
                $choices = array_filter($questionData['choices'] ?? [], function($choice) {
                    return !empty(trim($choice));
                });

                if (count($choices) < 2) {
                    throw new \Exception('Each question must have at least 2 non-empty choices.');
                }

                $question = QuizQuestion::create([
                    'quiz_id' => $quiz->quiz_id,
                    'question_text' => $questionData['question_text'],
                    'image_path' => $imagePath,
                    'background_color' => $questionData['background_color'] ?? '#F8C5C8',
                    'question_order' => $index + 1,
                    'points' => 1,
                ]);

                $correctAnswerIndex = (int)$questionData['correct_answer'];
                $choicesArray = array_values($choices); // Re-index array
                
                foreach ($choicesArray as $choiceIndex => $choiceText) {
                    QuizOption::create([
                        'question_id' => $question->question_id,
                        'option_text' => trim($choiceText),
                        'option_order' => $choiceIndex + 1,
                        'is_correct' => $choiceIndex == $correctAnswerIndex,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('quizzes.index')->with('success', 'Quiz created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating quiz: ' . $e->getMessage());
        }
    }

    // Teacher: List all quizzes
    public function index(Request $request)
    {
        $query = Quiz::where('teacher_id', Auth::id())
            ->with('level', 'questions', 'studentClass');
        
        // Filter by class if provided
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        
        $quizzes = $query->latest()->get();
        
        // Get all classes for this teacher for the filter dropdown
        $classes = StudentClass::where('teacher_id', Auth::id())->get();
        
        return view('quizzes.index', compact('quizzes', 'classes'));
    }

    // Teacher: Show quiz for viewing/editing/deleting
    public function show($id)
    {
        $quiz = Quiz::where('teacher_id', Auth::id())
            ->with(['questions.options' => function($query) {
                $query->orderBy('option_order');
            }])
            ->findOrFail($id);

        return view('quizzes.show', compact('quiz'));
    }

    // Teacher: Show edit form
    public function edit($id)
    {
        $quiz = Quiz::where('teacher_id', Auth::id())->findOrFail($id);
        $levels = Level::all();
        
        return view('quizzes.edit', compact('quiz', 'levels'));
    }

    // Teacher: Update quiz
    public function update(Request $request, $id)
    {
        $quiz = Quiz::where('teacher_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'level_id' => 'required|exists:levels,level_id',
            'timer_minutes' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Update quiz basic info only (questions are edited separately)
            $quiz->update([
                'title' => $request->title,
                'level_id' => $request->level_id,
                'description' => $request->description ?? null,
                'timer_minutes' => (int)$request->timer_minutes,
                // Keep existing max_score and passing_score based on current questions
            ]);
            
            // Refresh the model to ensure we have the latest data
            $quiz->refresh();

            DB::commit();
            return redirect()->route('quizzes.show', $quiz->quiz_id)->with('success', 'Quiz updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating quiz: ' . $e->getMessage());
        }
    }

    // Student: List available quizzes
    public function studentIndex()
    {
        $student = Auth::user()->student;
        if (!$student) {
            return view('student.quizzes', ['quizzes' => collect()]);
        }

        $quizzes = Quiz::where('class_id', $student->class_id)
            ->where('is_active', true)
            ->with('level', 'questions')
            ->latest()
            ->get();

        // Get all quiz attempts for this student to check which quizzes have been taken
        $attempts = QuizAttempt::where('student_id', $student->student_id)
            ->whereNotNull('submitted_at')
            ->with('quiz')
            ->get()
            ->keyBy('quiz_id');

        return view('student.quizzes', compact('quizzes', 'attempts'));
    }

    // Student: Show quiz for taking
    public function studentShow($id)
    {
        $student = Auth::user()->student;
        if (!$student) {
            return back()->with('error', 'Student profile not found');
        }

        // Get fresh quiz data from database (no caching)
        $quiz = Quiz::where('class_id', $student->class_id)
            ->where('is_active', true)
            ->with(['questions' => function($query) {
                $query->orderBy('question_order');
            }, 'questions.options' => function($query) {
                $query->orderBy('option_order');
            }])
            ->findOrFail($id);

        // Check if student has already submitted this quiz (prevent retaking)
        $hasCompletedAttempt = QuizAttempt::where('quiz_id', $quiz->quiz_id)
            ->where('student_id', $student->student_id)
            ->whereNotNull('submitted_at')
            ->exists();

        // If student has already taken the quiz, redirect to their latest attempt result
        if ($hasCompletedAttempt) {
            $latestAttempt = QuizAttempt::where('quiz_id', $quiz->quiz_id)
                ->where('student_id', $student->student_id)
                ->whereNotNull('submitted_at')
                ->latest()
                ->first();
            
            if ($latestAttempt) {
                return redirect()->route('student.quizzes.result', $latestAttempt->attempt_id)
                    ->with('error', 'You have already taken this quiz. You cannot retake it.');
            }
        }

        return view('student.quizzes.show', compact('quiz'));
    }

    // Teacher: Delete quiz
    public function destroy($id)
    {
        $quiz = Quiz::where('teacher_id', Auth::id())->findOrFail($id);
        
        // Delete related questions and options
        foreach ($quiz->questions as $question) {
            // Delete question image if exists
            if ($question->image_path) {
                Storage::disk('public')->delete($question->image_path);
            }
            $question->options()->delete();
        }
        $quiz->questions()->delete();
        
        // Delete quiz attempts and answers
        foreach ($quiz->attempts as $attempt) {
            $attempt->answers()->delete();
        }
        $quiz->attempts()->delete();
        
        $quiz->delete();
        
        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted successfully!');
    }

    // Student: Submit quiz answers
    public function submit(Request $request, $id)
    {
        $quiz = Quiz::with(['questions.options'])->findOrFail($id);
        $student = Auth::user()->student;

        if (!$student) {
            return back()->with('error', 'Student profile not found');
        }

        // Check if student has already submitted this quiz (prevent retaking)
        $hasCompletedAttempt = QuizAttempt::where('quiz_id', $quiz->quiz_id)
            ->where('student_id', $student->student_id)
            ->whereNotNull('submitted_at')
            ->exists();

        if ($hasCompletedAttempt) {
            $latestAttempt = QuizAttempt::where('quiz_id', $quiz->quiz_id)
                ->where('student_id', $student->student_id)
                ->whereNotNull('submitted_at')
                ->latest()
                ->first();
            
            if ($latestAttempt) {
                return redirect()->route('student.quizzes.result', $latestAttempt->attempt_id)
                    ->with('error', 'You have already submitted this quiz. You cannot retake it.');
            }
        }

        // If time expired, answers are optional (student may not have answered all questions)
        $validationRules = [
            'answers' => $request->has('time_expired') ? 'nullable|array' : 'required|array',
        ];
        
        if ($request->has('answers') && is_array($request->answers)) {
            foreach ($request->answers as $key => $value) {
                $validationRules["answers.{$key}"] = 'required|integer|exists:quiz_options,option_id';
            }
        }
        
        $request->validate($validationRules);

        DB::beginTransaction();
        try {
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->quiz_id,
                'student_id' => $student->student_id,
                'started_at' => now()->subMinutes($quiz->timer_minutes),
                'submitted_at' => now(),
                'score' => 0,
                'status' => $request->has('time_expired') && $request->time_expired == '1' ? 'time_expired' : 'completed',
            ]);

            $correctCount = 0;
            $answeredCount = 0;
            
            // Handle answers (may be empty if time expired)
            if ($request->has('answers') && is_array($request->answers)) {
                foreach ($request->answers as $questionId => $optionId) {
                    if ($optionId) {
                        $option = QuizOption::find($optionId);
                        $isCorrect = $option && $option->is_correct;

                        if ($isCorrect) {
                            $correctCount++;
                        }

                        StudentAnswer::create([
                            'attempt_id' => $attempt->attempt_id,
                            'question_id' => $questionId,
                            'selected_option_id' => $optionId,
                            'is_correct' => $isCorrect,
                        ]);
                        $answeredCount++;
                    }
                }
            }

            // Calculate score based on answered questions, or 0 if no answers
            $totalQuestions = $quiz->questions->count();
            $score = $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0;
            $attempt->score = $score;
            $attempt->save();

            DB::commit();
            
            // If time expired, redirect to grades page instead of result page
            if ($request->has('time_expired') && $request->time_expired == '1') {
                return redirect()->route('student.grades')->with('info', 'Quiz submitted automatically due to time expiration.');
            }
            
            return redirect()->route('student.quizzes.result', $attempt->attempt_id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error submitting quiz: ' . $e->getMessage());
        }
    }

    // Student: Show quiz result
    public function result($attemptId)
    {
        $attempt = QuizAttempt::with([
            'quiz.questions' => function($query) {
                $query->orderBy('question_order');
            },
            'quiz.questions.options' => function($query) {
                $query->orderBy('option_order');
            },
            'answers'
        ])->findOrFail($attemptId);

        if ($attempt->student_id != Auth::user()->student->student_id) {
            abort(403);
        }

        return view('quizzes.result', compact('attempt'));
    }

    // Teacher: Update individual question
    public function updateQuestion(Request $request, $quizId, $questionId)
    {
        $quiz = Quiz::where('teacher_id', Auth::id())->findOrFail($quizId);
        $question = QuizQuestion::where('quiz_id', $quiz->quiz_id)
            ->where('question_id', $questionId)
            ->firstOrFail();

        $request->validate([
            'question_text' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'background_color' => 'nullable|string|size:7',
            'choices' => 'required|array|min:2',
            'choices.*' => 'required|string',
            'correct_answer' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            // Handle image update
            $imagePath = $question->image_path;
            if ($request->hasFile('image')) {
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('quiz-questions', 'public');
            }

            // Update question
            $question->update([
                'question_text' => $request->question_text,
                'image_path' => $imagePath,
                'background_color' => $request->background_color ?? '#F8C5C8',
            ]);

            // Filter out empty choices
            $choices = array_filter($request->choices ?? [], function($choice) {
                return !empty(trim($choice));
            });

            if (count($choices) < 2) {
                throw new \Exception('Each question must have at least 2 non-empty choices.');
            }

            $correctAnswerIndex = (int)$request->correct_answer;
            $choicesArray = array_values($choices);
            $optionIds = $request->option_ids ?? [];

            // Get all existing options for this question
            $existingOptions = $question->options()->get()->keyBy('option_id');
            $usedOptionIds = [];

            // First, temporarily set all existing options to high order numbers to avoid unique constraint violations
            $tempOrderBase = 10000;
            foreach ($existingOptions as $opt) {
                $opt->update(['option_order' => $tempOrderBase + $opt->option_id]);
            }

            // Now process each choice and assign correct orders
            foreach ($choicesArray as $choiceIndex => $choiceText) {
                $targetOrder = $choiceIndex + 1;
                $optionId = isset($optionIds[$choiceIndex]) && !empty($optionIds[$choiceIndex]) ? $optionIds[$choiceIndex] : null;
                $option = null;
                
                // Try to find existing option by ID first
                if ($optionId && isset($existingOptions[$optionId])) {
                    $option = $existingOptions[$optionId];
                }
                
                // If not found by ID, try to reuse an unused existing option
                if (!$option) {
                    foreach ($existingOptions as $existingOption) {
                        if (!in_array($existingOption->option_id, $usedOptionIds)) {
                            $option = $existingOption;
                            break;
                        }
                    }
                }
                
                if ($option) {
                    // Update existing option with new order and data
                    $option->update([
                        'option_text' => trim($choiceText),
                        'option_order' => $targetOrder,
                        'is_correct' => $choiceIndex == $correctAnswerIndex,
                    ]);
                    $usedOptionIds[] = $option->option_id;
                } else {
                    // Create new option
                    $newOption = QuizOption::create([
                        'question_id' => $question->question_id,
                        'option_text' => trim($choiceText),
                        'option_order' => $targetOrder,
                        'is_correct' => $choiceIndex == $correctAnswerIndex,
                    ]);
                    $usedOptionIds[] = $newOption->option_id;
                }
            }

            // Delete options that were not used
            if (count($usedOptionIds) > 0) {
                $question->options()->whereNotIn('option_id', $usedOptionIds)->delete();
            } else {
                // If no options were used (shouldn't happen), delete all
                $question->options()->delete();
            }

            // Update quiz max_score and passing_score
            $questionCount = $quiz->questions()->count();
            $quiz->update([
                'max_score' => $questionCount,
                'passing_score' => ceil($questionCount * 0.6),
            ]);

            DB::commit();
            return redirect()->route('quizzes.show', $quiz->quiz_id)->with('success', 'Question updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating question: ' . $e->getMessage());
        }
    }

    // Teacher: Delete individual question
    public function deleteQuestion($quizId, $questionId)
    {
        $quiz = Quiz::where('teacher_id', Auth::id())->findOrFail($quizId);
        $question = QuizQuestion::where('quiz_id', $quiz->quiz_id)
            ->where('question_id', $questionId)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Delete question image if exists
            if ($question->image_path) {
                Storage::disk('public')->delete($question->image_path);
            }

            // Delete options
            $question->options()->delete();

            // Delete question
            $question->delete();

            // Reorder remaining questions
            $remainingQuestions = $quiz->questions()->orderBy('question_order')->get();
            foreach ($remainingQuestions as $index => $q) {
                $q->update(['question_order' => $index + 1]);
            }

            // Update quiz max_score and passing_score
            $questionCount = $quiz->questions()->count();
            $quiz->update([
                'max_score' => $questionCount,
                'passing_score' => ceil($questionCount * 0.6),
            ]);

            DB::commit();
            return redirect()->route('quizzes.show', $quiz->quiz_id)->with('success', 'Question deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting question: ' . $e->getMessage());
        }
    }
}
