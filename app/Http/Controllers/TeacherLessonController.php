<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\ClassLessonVisibility;
use App\Models\Schedule;
use App\Services\ScheduleGeneratorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeacherLessonController extends Controller
{
    protected $scheduleGenerator;

    public function __construct(ScheduleGeneratorService $scheduleGenerator)
    {
        $this->scheduleGenerator = $scheduleGenerator;
    }

    // Show all lessons for management
    public function index()
    {
        $teacher_id = Auth::id();
        // Get all classes for this teacher
        $classes = \App\Models\StudentClass::where('teacher_id', $teacher_id)->get();

        // Eager load lessons for each level, and for each lesson, eager load class visibilities
        $levels = \App\Models\Level::with(['lessons.classLessonVisibilities' => function($q) use ($teacher_id) {
            $q->where('teacher_id', $teacher_id);
        }])->get();

        // Check if schedule exists
        $hasSchedule = Schedule::where('teacher_id', $teacher_id)
            ->where('status', '!=', 'completed')
            ->exists();

        // Pass classes to the view as well
        return view('teacher.lessons', compact('levels', 'classes', 'hasSchedule'));
    }

    // Unlock lesson for students
    public function unlock(Request $request, $lesson_id)
    {
        $request->validate([
            'class_id' => 'required|exists:student_classes,class_id',
        ]);
        $class_id = $request->input('class_id');
        $teacher_id = Auth::id();
        
        // Check if this is the first lesson being shown (trigger for auto-schedule)
        $firstLesson = \App\Models\Level::with(['lessons' => function($q) use ($teacher_id) {
            $q->where('teacher_id', $teacher_id)->orderBy('lesson_order', 'asc');
        }])->orderBy('level_id', 'asc')->first();
        
        $isFirstLesson = false;
        if ($firstLesson && $firstLesson->lessons->isNotEmpty()) {
            $firstLessonId = $firstLesson->lessons->first()->lesson_id;
            $isFirstLesson = ($firstLessonId == $lesson_id);
        }
        
        // Check if schedule already exists
        $hasSchedule = Schedule::where('teacher_id', $teacher_id)
            ->where('status', '!=', 'completed')
            ->exists();
        
        // If no schedule exists, generate it (regardless of which lesson is clicked)
        // This allows schedule generation even if lessons were already shown before
        if (!$hasSchedule) {
            try {
                $this->scheduleGenerator->generateSchedule($teacher_id, $class_id);
                Log::info("Auto-schedule generated on lesson unlock", [
                    'teacher_id' => $teacher_id,
                    'class_id' => $class_id,
                    'lesson_id' => $lesson_id,
                    'is_first_lesson' => $isFirstLesson,
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to generate auto-schedule", [
                    'teacher_id' => $teacher_id,
                    'error' => $e->getMessage(),
                ]);
                // Continue with lesson unlock even if schedule generation fails
            }
        }
        
        $visibility = ClassLessonVisibility::updateOrCreate(
            [
                'lesson_id' => $lesson_id,
                'class_id' => $class_id,
            ],
            [
                'teacher_id' => $teacher_id,
                'is_visible' => true,
            ]
        );
        
        $message = 'Lesson unlocked for students!';
        if ($isFirstLesson && !$hasSchedule) {
            $message .= ' Auto-schedule has been activated.';
        }
        
        return back()->with('success', $message);
    }

    // Lock lesson for students
    public function lock(Request $request, $lesson_id)
    {
        $request->validate([
            'class_id' => 'required|exists:student_classes,class_id',
        ]);
        $class_id = $request->input('class_id');
        $teacher_id = Auth::id();
        $visibility = ClassLessonVisibility::updateOrCreate(
            [
                'lesson_id' => $lesson_id,
                'class_id' => $class_id,
            ],
            [
                'teacher_id' => $teacher_id,
                'is_visible' => false,
            ]
        );
        return back()->with('success', 'Lesson hidden from students!');
    }

    // View lesson content
    public function view($lesson)
    {
        // Get lesson by ID (route parameter)
        if (is_numeric($lesson)) {
            $lesson = \App\Models\Lesson::findOrFail($lesson);
        } elseif (!$lesson instanceof \App\Models\Lesson) {
            $lesson = \App\Models\Lesson::findOrFail($lesson);
        }
        
        return view('teacher.lesson-view', compact('lesson'));
    }
}
