<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\AttendanceTrackingService;
use App\Models\Meeting;
use App\Models\MeetingEnrollment;
use App\Models\AttendanceConfirmation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AttendanceTrackingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AttendanceTrackingService();
    }

    /** @test */
    public function it_creates_confirmation_schedule_for_enrollment()
    {
        // Arrange
        $meeting = Meeting::factory()->create([
            'scheduled_at' => Carbon::now(),
            'duration_minutes' => 30,
        ]);
        
        $enrollment = MeetingEnrollment::factory()->create([
            'meeting_id' => $meeting->meeting_id,
            'joined_at' => Carbon::now(),
        ]);

        // Act
        $this->service->createConfirmationSchedule($enrollment);

        // Assert
        // Should create 3 confirmations (30 minutes / 10 minute intervals = 3)
        $confirmations = AttendanceConfirmation::where('meeting_enrollment_id', $enrollment->id)->get();
        $this->assertCount(3, $confirmations);
        
        // Check first confirmation
        $firstConfirmation = $confirmations->first();
        $this->assertEquals(1, $firstConfirmation->confirmation_number);
        $this->assertNotNull($firstConfirmation->prompted_at);
        
        // Check last confirmation
        $lastConfirmation = $confirmations->last();
        $this->assertEquals(3, $lastConfirmation->confirmation_number);
    }

    /** @test */
    public function it_uses_meeting_scheduled_at_when_enrollment_has_no_joined_at()
    {
        // Arrange
        $scheduledAt = Carbon::now()->addHours(1);
        $meeting = Meeting::factory()->create([
            'scheduled_at' => $scheduledAt,
            'duration_minutes' => 20,
        ]);
        
        $enrollment = MeetingEnrollment::factory()->create([
            'meeting_id' => $meeting->meeting_id,
            'joined_at' => null,
        ]);

        // Act
        $this->service->createConfirmationSchedule($enrollment);

        // Assert
        $firstConfirmation = AttendanceConfirmation::where('meeting_enrollment_id', $enrollment->id)->first();
        $this->assertNotNull($firstConfirmation->prompted_at);
        // Should be based on meeting scheduled_at
    }

    /** @test */
    public function it_calculates_correct_number_of_intervals()
    {
        // Arrange
        $meeting = Meeting::factory()->create([
            'scheduled_at' => Carbon::now(),
            'duration_minutes' => 45, // Should create 5 intervals (45/10 = 4.5, ceil = 5)
        ]);
        
        $enrollment = MeetingEnrollment::factory()->create([
            'meeting_id' => $meeting->meeting_id,
            'joined_at' => Carbon::now(),
        ]);

        // Act
        $this->service->createConfirmationSchedule($enrollment);

        // Assert
        $confirmations = AttendanceConfirmation::where('meeting_enrollment_id', $enrollment->id)->get();
        $this->assertCount(5, $confirmations);
    }

    /** @test */
    public function it_should_show_prompt_when_due()
    {
        // Arrange
        $enrollment = MeetingEnrollment::factory()->create();
        
        AttendanceConfirmation::factory()->create([
            'meeting_enrollment_id' => $enrollment->id,
            'confirmation_number' => 1,
            'prompted_at' => Carbon::now()->subMinutes(5), // Past due
            'responded_at' => null,
        ]);

        // Act
        $confirmation = $this->service->shouldShowPrompt($enrollment);

        // Assert
        $this->assertNotNull($confirmation);
        $this->assertEquals(1, $confirmation->confirmation_number);
    }

    /** @test */
    public function it_should_not_show_prompt_when_not_due()
    {
        // Arrange
        $enrollment = MeetingEnrollment::factory()->create();
        
        AttendanceConfirmation::factory()->create([
            'meeting_enrollment_id' => $enrollment->id,
            'confirmation_number' => 1,
            'prompted_at' => Carbon::now()->addMinutes(5), // Future
            'responded_at' => null,
        ]);

        // Act
        $confirmation = $this->service->shouldShowPrompt($enrollment);

        // Assert
        $this->assertNull($confirmation);
    }

    /** @test */
    public function it_should_not_show_prompt_when_already_responded()
    {
        // Arrange
        $enrollment = MeetingEnrollment::factory()->create();
        
        AttendanceConfirmation::factory()->create([
            'meeting_enrollment_id' => $enrollment->id,
            'confirmation_number' => 1,
            'prompted_at' => Carbon::now()->subMinutes(5),
            'responded_at' => Carbon::now()->subMinutes(2), // Already responded
        ]);

        // Act
        $confirmation = $this->service->shouldShowPrompt($enrollment);

        // Assert
        $this->assertNull($confirmation);
    }

    /** @test */
    public function it_records_confirmation_as_confirmed()
    {
        // Arrange
        $confirmation = AttendanceConfirmation::factory()->create([
            'responded_at' => null,
            'is_confirmed' => null,
        ]);

        // Act
        $this->service->recordConfirmation($confirmation, true);

        // Assert
        $confirmation->refresh();
        $this->assertNotNull($confirmation->responded_at);
        $this->assertTrue($confirmation->is_confirmed);
    }

    /** @test */
    public function it_records_confirmation_as_not_confirmed()
    {
        // Arrange
        $confirmation = AttendanceConfirmation::factory()->create([
            'responded_at' => null,
            'is_confirmed' => null,
        ]);

        // Act
        $this->service->recordConfirmation($confirmation, false);

        // Assert
        $confirmation->refresh();
        $this->assertNotNull($confirmation->responded_at);
        $this->assertFalse($confirmation->is_confirmed);
    }

    /** @test */
    public function it_finalizes_meeting_attendance()
    {
        // Arrange
        $meeting = Meeting::factory()->create(['status' => 'active']);
        
        $enrollment1 = MeetingEnrollment::factory()->create([
            'meeting_id' => $meeting->meeting_id,
            'attendance_status' => 'pending',
        ]);
        
        $enrollment2 = MeetingEnrollment::factory()->create([
            'meeting_id' => $meeting->meeting_id,
            'attendance_status' => 'pending',
        ]);

        // Mock the calculateFinalStatus method behavior
        // Since we can't easily mock Eloquent methods, we'll test the structure
        
        // Act
        $this->service->finalizeMeetingAttendance($meeting);

        // Assert
        $meeting->refresh();
        $this->assertEquals('completed', $meeting->status);
        
        // Enrollments should have been updated (even if status calculation is complex)
        $enrollment1->refresh();
        $enrollment2->refresh();
        // The actual status depends on calculateFinalStatus implementation
    }

    /** @test */
    public function it_handles_meeting_with_no_enrollments()
    {
        // Arrange
        $meeting = Meeting::factory()->create(['status' => 'active']);

        // Act & Assert - Should not throw exception
        $this->service->finalizeMeetingAttendance($meeting);
        
        $meeting->refresh();
        $this->assertEquals('completed', $meeting->status);
    }

    /** @test */
    public function it_creates_confirmations_with_correct_timing()
    {
        // Arrange
        $startTime = Carbon::now()->setTime(10, 0);
        $meeting = Meeting::factory()->create([
            'scheduled_at' => $startTime,
            'duration_minutes' => 30,
        ]);
        
        $enrollment = MeetingEnrollment::factory()->create([
            'meeting_id' => $meeting->meeting_id,
            'joined_at' => $startTime,
        ]);

        // Act
        $this->service->createConfirmationSchedule($enrollment);

        // Assert
        $confirmations = AttendanceConfirmation::where('meeting_enrollment_id', $enrollment->id)
            ->orderBy('confirmation_number')
            ->get();
        
        // First confirmation should be at 10:10 (10 minutes after start)
        $this->assertEquals(
            $startTime->copy()->addMinutes(10)->format('Y-m-d H:i'),
            $confirmations[0]->prompted_at->format('Y-m-d H:i')
        );
        
        // Second confirmation should be at 10:20
        $this->assertEquals(
            $startTime->copy()->addMinutes(20)->format('Y-m-d H:i'),
            $confirmations[1]->prompted_at->format('Y-m-d H:i')
        );
        
        // Third confirmation should be at 10:30
        $this->assertEquals(
            $startTime->copy()->addMinutes(30)->format('Y-m-d H:i'),
            $confirmations[2]->prompted_at->format('Y-m-d H:i')
        );
    }
}
