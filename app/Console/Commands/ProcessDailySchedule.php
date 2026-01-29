<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\ScheduledEvent;
use App\Models\ClassLessonVisibility;
use App\Models\Assignment;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessDailySchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:process-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process daily schedule events - release lessons, assign assignments and quizzes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing daily schedule events...');
        
        $today = Carbon::now()->toDateString();
        
        // Get all due events from active schedules
        $dueEvents = ScheduledEvent::where('release_date', '<=', $today)
            ->where('status', 'pending')
            ->whereHas('schedule', function($query) {
                $query->where('status', 'active');
            })
            ->with(['schedule', 'lesson', 'level', 'assignment', 'quiz'])
            ->get();

        $lessonsReleased = 0;
        $assignmentsCreated = 0;
        $quizzesCreated = 0;

        foreach ($dueEvents as $event) {
            try {
                DB::beginTransaction();

                switch ($event->event_type) {
                    case 'lesson':
                        $this->releaseLesson($event);
                        $lessonsReleased++;
                        break;

                    case 'assignment':
                        $this->createAssignment($event);
                        $assignmentsCreated++;
                        break;

                    case 'quiz':
                        $this->createQuiz($event);
                        $quizzesCreated++;
                        break;
                }

                // Mark event as released
                $event->markAsReleased();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to process schedule event", [
                    'event_id' => $event->event_id,
                    'error' => $e->getMessage(),
                ]);
                $this->error("Failed to process event {$event->event_id}: {$e->getMessage()}");
            }
        }

        $this->info("Processed {$dueEvents->count()} events:");
        $this->info("  - Lessons released: {$lessonsReleased}");
        $this->info("  - Assignments created: {$assignmentsCreated}");
        $this->info("  - Quizzes created: {$quizzesCreated}");

        return Command::SUCCESS;
    }

    /**
     * Release a lesson by making it visible to students.
     */
    protected function releaseLesson(ScheduledEvent $event)
    {
        if (!$event->lesson || !$event->schedule) {
            throw new \Exception("Lesson or schedule not found for event {$event->event_id}");
        }

        $lesson = $event->lesson;
        $schedule = $event->schedule;
        $classId = $schedule->class_id;

        // If schedule has a specific class, make lesson visible for that class
        // Otherwise, make it visible for all classes taught by the teacher
        if ($classId) {
            ClassLessonVisibility::updateOrCreate(
                [
                    'lesson_id' => $lesson->lesson_id,
                    'class_id' => $classId,
                ],
                [
                    'teacher_id' => $schedule->teacher_id,
                    'is_visible' => true,
                ]
            );
        } else {
            // Get all classes for this teacher
            $classes = \App\Models\StudentClass::where('teacher_id', $schedule->teacher_id)->get();
            foreach ($classes as $class) {
                ClassLessonVisibility::updateOrCreate(
                    [
                        'lesson_id' => $lesson->lesson_id,
                        'class_id' => $class->class_id,
                    ],
                    [
                        'teacher_id' => $schedule->teacher_id,
                        'is_visible' => true,
                    ]
                );
            }
        }

        Log::info("Lesson released", [
            'lesson_id' => $lesson->lesson_id,
            'schedule_id' => $schedule->schedule_id,
        ]);
    }

    /**
     * Create an assignment based on scheduled event.
     */
    protected function createAssignment(ScheduledEvent $event)
    {
        if (!$event->lesson || !$event->schedule) {
            throw new \Exception("Lesson or schedule not found for assignment event {$event->event_id}");
        }

        $lesson = $event->lesson;
        $schedule = $event->schedule;
        $classId = $schedule->class_id;

        // Check if assignment already exists (by level and title)
        $existingAssignment = Assignment::where('level_id', $lesson->level_id)
            ->where('teacher_id', $schedule->teacher_id)
            ->where('title', 'like', '%' . $lesson->title . '%')
            ->when($classId, function($query) use ($classId) {
                return $query->where('class_id', $classId);
            })
            ->first();

        if ($existingAssignment) {
            // Update existing assignment
            $existingAssignment->update([
                'due_date' => $event->release_date,
            ]);
            $event->update(['assignment_id' => $existingAssignment->assignment_id]);
            return;
        }

        // Create new assignment
        $assignment = Assignment::create([
            'teacher_id' => $schedule->teacher_id,
            'title' => 'Assignment: ' . $lesson->title,
            'description' => 'Assignment for lesson: ' . $lesson->title,
            'level_id' => $lesson->level_id,
            'class_id' => $classId,
            'due_date' => $event->release_date,
        ]);

        $event->update(['assignment_id' => $assignment->assignment_id]);

        Log::info("Assignment created from schedule", [
            'assignment_id' => $assignment->assignment_id,
            'event_id' => $event->event_id,
        ]);
    }

    /**
     * Create a quiz when all lessons in a level are released.
     */
    protected function createQuiz(ScheduledEvent $event)
    {
        if (!$event->level || !$event->schedule) {
            throw new \Exception("Level or schedule not found for quiz event {$event->event_id}");
        }

        $level = $event->level;
        $schedule = $event->schedule;
        $classId = $schedule->class_id;

        // Check if all lessons in this level have been released
        $allLessonsReleased = ScheduledEvent::where('schedule_id', $schedule->schedule_id)
            ->where('level_id', $level->level_id)
            ->where('event_type', 'lesson')
            ->where('status', 'released')
            ->count();

        $totalLessons = $level->lessons()->where('teacher_id', $schedule->teacher_id)->count();

        if ($allLessonsReleased < $totalLessons) {
            // Not all lessons released yet, skip quiz creation
            Log::info("Quiz skipped - not all lessons released", [
                'level_id' => $level->level_id,
                'released' => $allLessonsReleased,
                'total' => $totalLessons,
            ]);
            return;
        }

        // Check if quiz already exists
        $existingQuiz = Quiz::where('level_id', $level->level_id)
            ->where('teacher_id', $schedule->teacher_id)
            ->when($classId, function($query) use ($classId) {
                return $query->where('class_id', $classId);
            })
            ->first();

        if ($existingQuiz) {
            // Update existing quiz
            $existingQuiz->update([
                'due_date' => $event->release_date,
                'is_active' => true,
            ]);
            $event->update(['quiz_id' => $existingQuiz->quiz_id]);
            return;
        }

        // Create new quiz
        $quiz = Quiz::create([
            'level_id' => $level->level_id,
            'class_id' => $classId,
            'teacher_id' => $schedule->teacher_id,
            'title' => 'Quiz: ' . $level->level_name,
            'description' => 'Quiz covering all lessons in ' . $level->level_name,
            'due_date' => $event->release_date,
            'is_active' => true,
        ]);

        $event->update(['quiz_id' => $quiz->quiz_id]);

        Log::info("Quiz created from schedule", [
            'quiz_id' => $quiz->quiz_id,
            'event_id' => $event->event_id,
        ]);
    }
}
