<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ScheduleEditorService;
use App\Models\Schedule;
use App\Models\ScheduledEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScheduleEditorServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ScheduleEditorService();
    }

    /** @test */
    public function it_updates_event_release_date()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $event = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'release_date' => Carbon::now()->toDateString(),
        ]);
        
        $newDate = Carbon::now()->addDays(5)->toDateString();

        // Act
        $updated = $this->service->updateEvent($event, ['release_date' => $newDate]);

        // Assert
        $this->assertEquals($newDate, $updated->release_date);
        $event->refresh();
        $this->assertEquals($newDate, $event->release_date);
    }

    /** @test */
    public function it_updates_event_type()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $event = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'event_type' => 'lesson',
        ]);

        // Act
        $updated = $this->service->updateEvent($event, ['event_type' => 'assignment']);

        // Assert
        $this->assertEquals('assignment', $updated->event_type);
    }

    /** @test */
    public function it_updates_multiple_event_fields()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $event = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
        ]);

        // Act
        $updated = $this->service->updateEvent($event, [
            'release_date' => Carbon::now()->addDays(10)->toDateString(),
            'event_type' => 'quiz',
            'admin_notes' => 'Updated by admin',
        ]);

        // Assert
        $this->assertEquals('quiz', $updated->event_type);
        $this->assertEquals('Updated by admin', $updated->admin_notes);
    }

    /** @test */
    public function it_marks_event_as_edited_by_admin()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $event = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'edited_by_admin' => false,
        ]);
        $adminId = 1;

        // Act
        $updated = $this->service->updateEvent($event, ['release_date' => Carbon::now()->toDateString()], $adminId);

        // Assert
        $this->assertTrue($updated->edited_by_admin);
        $this->assertEquals($adminId, $updated->admin_id);
    }

    /** @test */
    public function it_creates_new_scheduled_event()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $eventData = [
            'event_type' => 'lesson',
            'release_date' => Carbon::now()->addDays(5)->toDateString(),
            'lesson_id' => 1,
            'level_id' => 1,
        ];

        // Act
        $event = $this->service->createEvent($schedule, $eventData);

        // Assert
        $this->assertInstanceOf(ScheduledEvent::class, $event);
        $this->assertEquals($schedule->schedule_id, $event->schedule_id);
        $this->assertEquals('lesson', $event->event_type);
        $this->assertEquals('pending', $event->status);
        $this->assertEquals(1, $event->lesson_id);
        $this->assertEquals(1, $event->level_id);
    }

    /** @test */
    public function it_creates_event_with_admin_flag()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $adminId = 1;
        $eventData = [
            'event_type' => 'assignment',
            'release_date' => Carbon::now()->toDateString(),
        ];

        // Act
        $event = $this->service->createEvent($schedule, $eventData, $adminId);

        // Assert
        $this->assertTrue($event->edited_by_admin);
        $this->assertEquals($adminId, $event->admin_id);
    }

    /** @test */
    public function it_deletes_event()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $event = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
        ]);
        $eventId = $event->event_id;

        // Act
        $this->service->deleteEvent($event);

        // Assert
        $this->assertDatabaseMissing('scheduled_events', ['event_id' => $eventId]);
    }

    /** @test */
    public function it_shifts_subsequent_events_when_deleting()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $baseDate = Carbon::parse('2024-01-01');
        
        $event1 = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'release_date' => $baseDate->copy()->toDateString(),
        ]);
        
        $event2 = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'release_date' => $baseDate->copy()->addWeek()->toDateString(),
        ]);
        
        $event3 = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'release_date' => $baseDate->copy()->addWeeks(2)->toDateString(),
        ]);

        // Act
        $this->service->deleteEvent($event1, true);

        // Assert
        $event2->refresh();
        $event3->refresh();
        
        // Should be shifted back by one week
        $this->assertEquals($baseDate->copy()->toDateString(), $event2->release_date);
        $this->assertEquals($baseDate->copy()->addWeek()->toDateString(), $event3->release_date);
    }

    /** @test */
    public function it_does_not_shift_events_when_flag_is_false()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $baseDate = Carbon::parse('2024-01-01');
        
        $event1 = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'release_date' => $baseDate->copy()->toDateString(),
        ]);
        
        $event2 = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'release_date' => $baseDate->copy()->addWeek()->toDateString(),
        ]);
        $originalDate = $event2->release_date;

        // Act
        $this->service->deleteEvent($event1, false);

        // Assert
        $event2->refresh();
        $this->assertEquals($originalDate, $event2->release_date);
    }

    /** @test */
    public function it_performs_bulk_update_on_events()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $event1 = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'status' => 'pending',
        ]);
        $event2 = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'status' => 'pending',
        ]);

        // Act
        $count = $this->service->bulkUpdateEvents($schedule, [$event1->event_id, $event2->event_id], [
            'status' => 'active',
        ]);

        // Assert
        $this->assertEquals(2, $count);
        $event1->refresh();
        $event2->refresh();
        $this->assertEquals('active', $event1->status);
        $this->assertEquals('active', $event2->status);
    }

    /** @test */
    public function it_shifts_dates_in_bulk_update()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $baseDate = Carbon::parse('2024-01-01');
        
        $event1 = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'release_date' => $baseDate->copy()->toDateString(),
        ]);
        $event2 = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'release_date' => $baseDate->copy()->addDays(5)->toDateString(),
        ]);

        // Act
        $count = $this->service->bulkUpdateEvents($schedule, [$event1->event_id, $event2->event_id], [
            'shift_days' => 3,
        ]);

        // Assert
        $this->assertEquals(2, $count);
        $event1->refresh();
        $event2->refresh();
        $this->assertEquals($baseDate->copy()->addDays(3)->toDateString(), $event1->release_date);
        $this->assertEquals($baseDate->copy()->addDays(8)->toDateString(), $event2->release_date);
    }

    /** @test */
    public function it_reorders_events()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $event1 = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'release_date' => Carbon::parse('2024-01-01')->toDateString(),
        ]);
        $event2 = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'release_date' => Carbon::parse('2024-01-02')->toDateString(),
        ]);

        $newDate1 = Carbon::parse('2024-01-10')->toDateString();
        $newDate2 = Carbon::parse('2024-01-05')->toDateString();

        // Act
        $this->service->reorderEvents($schedule, [
            $event1->event_id => $newDate1,
            $event2->event_id => $newDate2,
        ]);

        // Assert
        $event1->refresh();
        $event2->refresh();
        $this->assertEquals($newDate1, $event1->release_date);
        $this->assertEquals($newDate2, $event2->release_date);
        $this->assertTrue($event1->edited_by_admin);
        $this->assertTrue($event2->edited_by_admin);
    }

    /** @test */
    public function it_handles_update_with_only_some_fields()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $event = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
            'event_type' => 'lesson',
            'release_date' => Carbon::now()->toDateString(),
        ]);
        $originalType = $event->event_type;

        // Act - Only update release_date
        $updated = $this->service->updateEvent($event, ['release_date' => Carbon::now()->addDays(5)->toDateString()]);

        // Assert
        $this->assertEquals($originalType, $updated->event_type); // Should remain unchanged
        $this->assertNotEquals($event->release_date, $updated->release_date);
    }

    /** @test */
    public function it_rolls_back_on_exception_during_update()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $event = ScheduledEvent::factory()->create([
            'schedule_id' => $schedule->schedule_id,
        ]);
        $originalDate = $event->release_date;

        // Mock an exception scenario - invalid date format
        try {
            $this->service->updateEvent($event, ['release_date' => 'invalid-date']);
        } catch (\Exception $e) {
            // Expected to throw
        }

        // Assert - Event should remain unchanged
        $event->refresh();
        $this->assertEquals($originalDate, $event->release_date);
    }

    /** @test */
    public function it_handles_optional_fields_in_create_event()
    {
        // Arrange
        $schedule = Schedule::factory()->create();
        $eventData = [
            'event_type' => 'quiz',
            'release_date' => Carbon::now()->toDateString(),
            'level_id' => 1,
            // lesson_id, assignment_id, quiz_id are optional
        ];

        // Act
        $event = $this->service->createEvent($schedule, $eventData);

        // Assert
        $this->assertNotNull($event);
        $this->assertEquals('quiz', $event->event_type);
        $this->assertNull($event->lesson_id);
        $this->assertEquals(1, $event->level_id);
    }
}
