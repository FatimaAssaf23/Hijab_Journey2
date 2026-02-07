<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\MeetingEnrollmentService;
use App\Models\Meeting;
use App\Models\MeetingEnrollment;
use App\Models\Student;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class MeetingEnrollmentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MeetingEnrollmentService();
    }

    /** @test */
    public function it_syncs_enrollments_for_meeting_with_class()
    {
        // Arrange
        $classId = 1;
        $meeting = Meeting::factory()->create(['class_id' => $classId]);
        
        $user1 = User::factory()->create(['role' => 'student']);
        $user2 = User::factory()->create(['role' => 'student']);
        
        $student1 = Student::factory()->create(['class_id' => $classId, 'user_id' => $user1->user_id]);
        $student2 = Student::factory()->create(['class_id' => $classId, 'user_id' => $user2->user_id]);

        // Act
        $result = $this->service->syncEnrollmentsForMeeting($meeting);

        // Assert
        $this->assertEquals(2, $result['created']);
        $this->assertEquals(0, $result['existing']);
        $this->assertEquals(2, $result['total']);
        
        $this->assertDatabaseHas('meeting_enrollments', [
            'meeting_id' => $meeting->meeting_id,
            'student_id' => $user1->user_id,
            'attendance_status' => 'pending',
        ]);
        
        $this->assertDatabaseHas('meeting_enrollments', [
            'meeting_id' => $meeting->meeting_id,
            'student_id' => $user2->user_id,
            'attendance_status' => 'pending',
        ]);
    }

    /** @test */
    public function it_does_not_create_duplicate_enrollments()
    {
        // Arrange
        $classId = 1;
        $meeting = Meeting::factory()->create(['class_id' => $classId]);
        
        $user = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create(['class_id' => $classId, 'user_id' => $user->user_id]);
        
        // Create enrollment first time
        $this->service->syncEnrollmentsForMeeting($meeting);
        
        // Act - sync again
        $result = $this->service->syncEnrollmentsForMeeting($meeting);

        // Assert
        $this->assertEquals(0, $result['created']);
        $this->assertEquals(1, $result['existing']);
        $this->assertEquals(1, $result['total']);
        
        // Should still have only one enrollment
        $this->assertEquals(1, MeetingEnrollment::where('meeting_id', $meeting->meeting_id)->count());
    }

    /** @test */
    public function it_handles_meeting_without_class_id()
    {
        // Arrange
        $meeting = Meeting::factory()->create(['class_id' => null]);

        // Act
        $result = $this->service->syncEnrollmentsForMeeting($meeting);

        // Assert
        $this->assertEquals(0, $result['created']);
        $this->assertEquals(0, $result['existing']);
        $this->assertEquals(0, $result['total']);
    }

    /** @test */
    public function it_skips_students_without_user()
    {
        // Arrange
        $classId = 1;
        $meeting = Meeting::factory()->create(['class_id' => $classId]);
        
        $user = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create(['class_id' => $classId, 'user_id' => $user->user_id]);
        
        // Student without user
        Student::factory()->create(['class_id' => $classId, 'user_id' => null]);

        // Act
        $result = $this->service->syncEnrollmentsForMeeting($meeting);

        // Assert
        $this->assertEquals(1, $result['created']);
        $this->assertEquals(1, $result['total']);
    }

    /** @test */
    public function it_syncs_all_meetings_without_enrollments()
    {
        // Arrange
        $classId = 1;
        $meeting1 = Meeting::factory()->create(['class_id' => $classId]);
        $meeting2 = Meeting::factory()->create(['class_id' => $classId]);
        
        $user = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create(['class_id' => $classId, 'user_id' => $user->user_id]);

        // Act
        $result = $this->service->syncAllMeetings();

        // Assert
        $this->assertEquals(2, $result['meetings_processed']);
        $this->assertEquals(2, $result['enrollments_created']);
        $this->assertEmpty($result['errors']);
    }

    /** @test */
    public function it_skips_meetings_that_already_have_enrollments()
    {
        // Arrange
        $classId = 1;
        $meeting1 = Meeting::factory()->create(['class_id' => $classId]);
        $meeting2 = Meeting::factory()->create(['class_id' => $classId]);
        
        $user = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create(['class_id' => $classId, 'user_id' => $user->user_id]);
        
        // Create enrollment for first meeting
        MeetingEnrollment::factory()->create([
            'meeting_id' => $meeting1->meeting_id,
            'student_id' => $user->user_id,
        ]);

        // Act
        $result = $this->service->syncAllMeetings();

        // Assert
        $this->assertEquals(1, $result['meetings_processed']); // Only meeting2
        $this->assertEquals(1, $result['enrollments_created']);
    }

    /** @test */
    public function it_handles_errors_during_sync_all_meetings()
    {
        // Arrange
        $meeting = Meeting::factory()->create(['class_id' => 999]); // Invalid class_id

        // Act
        $result = $this->service->syncAllMeetings();

        // Assert
        $this->assertArrayHasKey('errors', $result);
        // Should still process other meetings if any
    }

    /** @test */
    public function it_gets_meetings_for_student_from_enrollments()
    {
        // Arrange
        $user = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create(['user_id' => $user->user_id]);
        
        $meeting1 = Meeting::factory()->create(['scheduled_at' => now()->addDays(1)]);
        $meeting2 = Meeting::factory()->create(['scheduled_at' => now()->addDays(2)]);
        
        MeetingEnrollment::factory()->create([
            'meeting_id' => $meeting1->meeting_id,
            'student_id' => $user->user_id,
        ]);
        
        MeetingEnrollment::factory()->create([
            'meeting_id' => $meeting2->meeting_id,
            'student_id' => $user->user_id,
        ]);

        // Act
        $meetings = $this->service->getMeetingsForStudent($user);

        // Assert
        $this->assertCount(2, $meetings);
        // Should be ordered by scheduled_at desc
        $this->assertEquals($meeting2->meeting_id, $meetings->first()->meeting_id);
    }

    /** @test */
    public function it_falls_back_to_class_meetings_when_no_enrollments()
    {
        // Arrange
        $classId = 1;
        $user = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create(['class_id' => $classId, 'user_id' => $user->user_id]);
        
        $meeting = Meeting::factory()->create([
            'class_id' => $classId,
            'scheduled_at' => now()->addDays(1),
        ]);

        // Act
        $meetings = $this->service->getMeetingsForStudent($user);

        // Assert
        $this->assertCount(1, $meetings);
        $this->assertEquals($meeting->meeting_id, $meetings->first()->meeting_id);
        
        // Should have created enrollment
        $this->assertDatabaseHas('meeting_enrollments', [
            'meeting_id' => $meeting->meeting_id,
            'student_id' => $user->user_id,
        ]);
    }

    /** @test */
    public function it_returns_empty_collection_for_student_without_profile()
    {
        // Arrange
        $user = User::factory()->create(['role' => 'student']);
        // No student profile

        // Act
        $meetings = $this->service->getMeetingsForStudent($user);

        // Assert
        $this->assertCount(0, $meetings);
    }

    /** @test */
    public function it_returns_empty_collection_for_student_without_class()
    {
        // Arrange
        $user = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create(['class_id' => null, 'user_id' => $user->user_id]);

        // Act
        $meetings = $this->service->getMeetingsForStudent($user);

        // Assert
        $this->assertCount(0, $meetings);
    }

    /** @test */
    public function it_gets_attendance_data_for_meeting()
    {
        // Arrange
        $classId = 1;
        $meeting = Meeting::factory()->create(['class_id' => $classId]);
        
        $user1 = User::factory()->create(['role' => 'student']);
        $user2 = User::factory()->create(['role' => 'student']);
        
        $student1 = Student::factory()->create(['class_id' => $classId, 'user_id' => $user1->user_id]);
        $student2 = Student::factory()->create(['class_id' => $classId, 'user_id' => $user2->user_id]);
        
        Attendance::factory()->create([
            'meeting_id' => $meeting->meeting_id,
            'user_id' => $user1->user_id,
            'joined_at' => now(),
        ]);

        // Act
        $data = $this->service->getAttendanceDataForMeeting($meeting);

        // Assert
        $this->assertArrayHasKey('attendances', $data);
        $this->assertArrayHasKey('allStudents', $data);
        $this->assertArrayHasKey('system', $data);
        $this->assertEquals('simplified', $data['system']);
        $this->assertCount(1, $data['attendances']);
        $this->assertCount(2, $data['allStudents']);
    }

    /** @test */
    public function it_ensures_meeting_has_enrollments()
    {
        // Arrange
        $classId = 1;
        $meeting = Meeting::factory()->create(['class_id' => $classId]);
        
        $user = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create(['class_id' => $classId, 'user_id' => $user->user_id]);

        // Act
        $this->service->ensureMeetingHasEnrollments($meeting);

        // Assert
        $this->assertDatabaseHas('meeting_enrollments', [
            'meeting_id' => $meeting->meeting_id,
            'student_id' => $user->user_id,
        ]);
    }

    /** @test */
    public function it_does_not_create_enrollments_if_already_exist()
    {
        // Arrange
        $classId = 1;
        $meeting = Meeting::factory()->create(['class_id' => $classId]);
        
        $user = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create(['class_id' => $classId, 'user_id' => $user->user_id]);
        
        MeetingEnrollment::factory()->create([
            'meeting_id' => $meeting->meeting_id,
            'student_id' => $user->user_id,
        ]);

        // Act
        $this->service->ensureMeetingHasEnrollments($meeting);

        // Assert
        $this->assertEquals(1, MeetingEnrollment::where('meeting_id', $meeting->meeting_id)->count());
    }

    /** @test */
    public function it_handles_meeting_without_class_id_in_ensure_method()
    {
        // Arrange
        $meeting = Meeting::factory()->create(['class_id' => null]);

        // Act & Assert - Should not throw exception
        $this->service->ensureMeetingHasEnrollments($meeting);
        
        $this->assertEquals(0, MeetingEnrollment::where('meeting_id', $meeting->meeting_id)->count());
    }
}
