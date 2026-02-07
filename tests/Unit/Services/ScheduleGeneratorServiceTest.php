<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ScheduleGeneratorService;
use App\Models\Schedule;
use App\Models\ScheduledEvent;
use App\Models\Level;
use App\Models\Lesson;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScheduleGeneratorServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ScheduleGeneratorService();
    }

    /** @test */
    public function it_generates_schedule_for_teacher()
    {
        // Arrange
        $teacher = Teacher::factory()->create();
        $classId = 1;
        $startDate = Carbon::now();
        
        $level = Level::factory()->create(['class_id' => $classId]);
        $lesson1 = Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
            'lesson_order' => 1,
        ]);
        $lesson2 = Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
            'lesson_order' => 2,
        ]);

        // Act
        $schedule = $this->service->generateSchedule($teacher->teacher_id, $classId, $startDate);

        // Assert
        $this->assertInstanceOf(Schedule::class, $schedule);
        $this->assertEquals($teacher->teacher_id, $schedule->teacher_id);
        $this->assertEquals($classId, $schedule->class_id);
        $this->assertEquals('active', $schedule->status);
        
        // Should have events for lessons and assignments
        $events = ScheduledEvent::where('schedule_id', $schedule->schedule_id)->get();
        $this->assertGreaterThan(0, $events->count());
        
        // Should have lesson events
        $lessonEvents = $events->where('event_type', 'lesson');
        $this->assertGreaterThan(0, $lessonEvents->count());
        
        // Should have assignment events
        $assignmentEvents = $events->where('event_type', 'assignment');
        $this->assertGreaterThan(0, $assignmentEvents->count());
    }

    /** @test */
    public function it_throws_exception_when_active_schedule_exists()
    {
        // Arrange
        $teacher = Teacher::factory()->create();
        $classId = 1;
        
        Schedule::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'class_id' => $classId,
            'status' => 'active',
        ]);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('An active schedule already exists');
        
        $this->service->generateSchedule($teacher->teacher_id, $classId);
    }

    /** @test */
    public function it_allows_generating_schedule_when_previous_is_completed()
    {
        // Arrange
        $teacher = Teacher::factory()->create();
        $classId = 1;
        
        Schedule::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'class_id' => $classId,
            'status' => 'completed',
        ]);
        
        $level = Level::factory()->create(['class_id' => $classId]);
        Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
        ]);

        // Act
        $schedule = $this->service->generateSchedule($teacher->teacher_id, $classId);

        // Assert
        $this->assertInstanceOf(Schedule::class, $schedule);
        $this->assertEquals('active', $schedule->status);
    }

    /** @test */
    public function it_schedules_assignments_two_days_after_lessons()
    {
        // Arrange
        $teacher = Teacher::factory()->create();
        $classId = 1;
        $startDate = Carbon::parse('2024-01-01');
        
        $level = Level::factory()->create(['class_id' => $classId]);
        $lesson = Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
            'lesson_order' => 1,
        ]);

        // Act
        $schedule = $this->service->generateSchedule($teacher->teacher_id, $classId, $startDate);

        // Assert
        $lessonEvent = ScheduledEvent::where('schedule_id', $schedule->schedule_id)
            ->where('event_type', 'lesson')
            ->where('lesson_id', $lesson->lesson_id)
            ->first();
        
        $assignmentEvent = ScheduledEvent::where('schedule_id', $schedule->schedule_id)
            ->where('event_type', 'assignment')
            ->where('lesson_id', $lesson->lesson_id)
            ->first();
        
        $this->assertNotNull($lessonEvent);
        $this->assertNotNull($assignmentEvent);
        
        $lessonDate = Carbon::parse($lessonEvent->release_date);
        $assignmentDate = Carbon::parse($assignmentEvent->release_date);
        
        $this->assertEquals(2, $lessonDate->diffInDays($assignmentDate));
    }

    /** @test */
    public function it_schedules_lessons_one_week_apart()
    {
        // Arrange
        $teacher = Teacher::factory()->create();
        $classId = 1;
        $startDate = Carbon::parse('2024-01-01');
        
        $level = Level::factory()->create(['class_id' => $classId]);
        $lesson1 = Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
            'lesson_order' => 1,
        ]);
        $lesson2 = Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
            'lesson_order' => 2,
        ]);

        // Act
        $schedule = $this->service->generateSchedule($teacher->teacher_id, $classId, $startDate);

        // Assert
        $lesson1Event = ScheduledEvent::where('schedule_id', $schedule->schedule_id)
            ->where('event_type', 'lesson')
            ->where('lesson_id', $lesson1->lesson_id)
            ->first();
        
        $lesson2Event = ScheduledEvent::where('schedule_id', $schedule->schedule_id)
            ->where('event_type', 'lesson')
            ->where('lesson_id', $lesson2->lesson_id)
            ->first();
        
        $date1 = Carbon::parse($lesson1Event->release_date);
        $date2 = Carbon::parse($lesson2Event->release_date);
        
        $this->assertEquals(7, $date1->diffInDays($date2));
    }

    /** @test */
    public function it_schedules_quiz_when_level_is_complete()
    {
        // Arrange
        $teacher = Teacher::factory()->create();
        $classId = 1;
        $startDate = Carbon::parse('2024-01-01');
        
        $level = Level::factory()->create(['class_id' => $classId]);
        $lesson = Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
            'lesson_order' => 1,
        ]);

        // Act
        $schedule = $this->service->generateSchedule($teacher->teacher_id, $classId, $startDate);

        // Assert
        $quizEvent = ScheduledEvent::where('schedule_id', $schedule->schedule_id)
            ->where('event_type', 'quiz')
            ->where('level_id', $level->level_id)
            ->first();
        
        $this->assertNotNull($quizEvent);
    }

    /** @test */
    public function it_extends_schedule_with_new_lessons()
    {
        // Arrange
        $teacher = Teacher::factory()->create();
        $classId = 1;
        $startDate = Carbon::parse('2024-01-01');
        
        $level = Level::factory()->create(['class_id' => $classId]);
        $lesson1 = Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
            'lesson_order' => 1,
        ]);
        
        // Generate initial schedule
        $schedule = $this->service->generateSchedule($teacher->teacher_id, $classId, $startDate);
        
        // Add new lesson
        $lesson2 = Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
            'lesson_order' => 2,
        ]);

        // Act
        $this->service->extendSchedule($schedule);

        // Assert
        $newLessonEvent = ScheduledEvent::where('schedule_id', $schedule->schedule_id)
            ->where('event_type', 'lesson')
            ->where('lesson_id', $lesson2->lesson_id)
            ->first();
        
        $this->assertNotNull($newLessonEvent);
    }

    /** @test */
    public function it_does_not_extend_inactive_schedule()
    {
        // Arrange
        $schedule = Schedule::factory()->create(['status' => 'completed']);
        
        // Act
        $this->service->extendSchedule($schedule);

        // Assert - Should not create new events
        $initialCount = ScheduledEvent::where('schedule_id', $schedule->schedule_id)->count();
        // No new events should be created
    }

    /** @test */
    public function it_skips_already_scheduled_lessons_when_extending()
    {
        // Arrange
        $teacher = Teacher::factory()->create();
        $classId = 1;
        $startDate = Carbon::parse('2024-01-01');
        
        $level = Level::factory()->create(['class_id' => $classId]);
        $lesson = Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
            'lesson_order' => 1,
        ]);
        
        $schedule = $this->service->generateSchedule($teacher->teacher_id, $classId, $startDate);
        
        $initialEventCount = ScheduledEvent::where('schedule_id', $schedule->schedule_id)->count();

        // Act - Extend again
        $this->service->extendSchedule($schedule);

        // Assert - Should not duplicate events
        $finalEventCount = ScheduledEvent::where('schedule_id', $schedule->schedule_id)->count();
        $this->assertEquals($initialEventCount, $finalEventCount);
    }

    /** @test */
    public function it_previews_schedule_without_creating()
    {
        // Arrange
        $teacher = Teacher::factory()->create();
        $classId = 1;
        $startDate = Carbon::parse('2024-01-01');
        
        $level = Level::factory()->create(['class_id' => $classId, 'level_name' => 'Level 1']);
        $lesson = Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
            'lesson_order' => 1,
            'title' => 'Test Lesson',
        ]);

        // Act
        $preview = $this->service->previewSchedule($teacher->teacher_id, $classId, $startDate);

        // Assert
        $this->assertIsArray($preview);
        $this->assertGreaterThan(0, count($preview));
        
        // Should have lesson entry
        $lessonEntry = collect($preview)->firstWhere('type', 'lesson');
        $this->assertNotNull($lessonEntry);
        $this->assertEquals('Test Lesson', $lessonEntry['title']);
        $this->assertEquals('Level 1', $lessonEntry['level']);
        
        // Should have assignment entry
        $assignmentEntry = collect($preview)->firstWhere('type', 'assignment');
        $this->assertNotNull($assignmentEntry);
    }

    /** @test */
    public function it_uses_current_date_as_default_start_date()
    {
        // Arrange
        $teacher = Teacher::factory()->create();
        $classId = 1;
        
        $level = Level::factory()->create(['class_id' => $classId]);
        Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
        ]);

        // Act
        $schedule = $this->service->generateSchedule($teacher->teacher_id, $classId);

        // Assert
        $this->assertNotNull($schedule->started_at);
        // Should be today or very recent
        $this->assertTrue(
            Carbon::parse($schedule->started_at)->isToday() ||
            Carbon::parse($schedule->started_at)->isYesterday()
        );
    }

    /** @test */
    public function it_creates_events_with_correct_structure()
    {
        // Arrange
        $teacher = Teacher::factory()->create();
        $classId = 1;
        $startDate = Carbon::parse('2024-01-01');
        
        $level = Level::factory()->create(['class_id' => $classId]);
        $lesson = Lesson::factory()->create([
            'teacher_id' => $teacher->teacher_id,
            'level_id' => $level->level_id,
            'lesson_order' => 1,
        ]);

        // Act
        $schedule = $this->service->generateSchedule($teacher->teacher_id, $classId, $startDate);

        // Assert
        $event = ScheduledEvent::where('schedule_id', $schedule->schedule_id)
            ->where('event_type', 'lesson')
            ->first();
        
        $this->assertNotNull($event);
        $this->assertEquals($schedule->schedule_id, $event->schedule_id);
        $this->assertEquals('lesson', $event->event_type);
        $this->assertEquals('pending', $event->status);
        $this->assertEquals($lesson->lesson_id, $event->lesson_id);
        $this->assertEquals($level->level_id, $event->level_id);
        $this->assertFalse($event->edited_by_admin);
    }
}
