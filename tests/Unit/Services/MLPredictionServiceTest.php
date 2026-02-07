<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\MLPredictionService;
use App\Models\Student;
use App\Models\Level;
use App\Models\StudentLessonProgress;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\StudentRiskPrediction;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class MLPredictionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $apiUrl;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->apiUrl = 'http://localhost:5000';
        Config::set('services.ml_api.url', $this->apiUrl);
        
        $this->service = new MLPredictionService();
    }

    /** @test */
    public function it_calculates_student_features_correctly()
    {
        // Arrange
        $student = Student::factory()->create(['class_id' => 1]);
        $level = Level::factory()->create(['level_id' => 1, 'class_id' => 1]);
        $lesson = Lesson::factory()->create(['level_id' => 1]);
        
        StudentLessonProgress::factory()->create([
            'student_id' => $student->student_id,
            'lesson_id' => $lesson->lesson_id,
            'watched_percentage' => 80,
            'video_completed' => true,
            'last_activity_at' => Carbon::now()->subDays(2),
        ]);
        
        StudentLessonProgress::factory()->create([
            'student_id' => $student->student_id,
            'lesson_id' => $lesson->lesson_id,
            'watched_percentage' => 60,
            'video_completed' => false,
            'last_activity_at' => Carbon::now()->subDays(1),
        ]);

        // Act
        $features = $this->service->calculateStudentFeatures($student->student_id);

        // Assert
        $this->assertNotNull($features);
        $this->assertEquals(70.0, $features['avg_watch_pct']); // (80 + 60) / 2
        $this->assertEquals(0.5, $features['completion_rate']); // 1 out of 2 completed
        $this->assertEquals(1, $features['days_inactive']); // Most recent activity
        $this->assertEquals(2, $features['lessons_completed']);
        $this->assertEquals(1, $features['current_level_id']);
    }

    /** @test */
    public function it_returns_null_when_student_has_no_level()
    {
        // Arrange
        $student = Student::factory()->create(['class_id' => null]);

        // Act
        $features = $this->service->calculateStudentFeatures($student->student_id);

        // Assert
        $this->assertNull($features);
    }

    /** @test */
    public function it_returns_null_when_student_has_no_lesson_progress()
    {
        // Arrange
        $student = Student::factory()->create(['class_id' => 1]);
        Level::factory()->create(['level_id' => 1, 'class_id' => 1]);

        // Act
        $features = $this->service->calculateStudentFeatures($student->student_id);

        // Assert
        $this->assertNull($features);
    }

    /** @test */
    public function it_calculates_days_inactive_from_last_activity()
    {
        // Arrange
        $student = Student::factory()->create(['class_id' => 1]);
        $level = Level::factory()->create(['level_id' => 1, 'class_id' => 1]);
        $lesson = Lesson::factory()->create(['level_id' => 1]);
        
        StudentLessonProgress::factory()->create([
            'student_id' => $student->student_id,
            'lesson_id' => $lesson->lesson_id,
            'last_activity_at' => Carbon::now()->subDays(5),
        ]);

        // Act
        $features = $this->service->calculateStudentFeatures($student->student_id);

        // Assert
        $this->assertNotNull($features);
        $this->assertEquals(5, $features['days_inactive']);
    }

    /** @test */
    public function it_calculates_average_quiz_score()
    {
        // Arrange
        $student = Student::factory()->create(['class_id' => 1]);
        $level = Level::factory()->create(['level_id' => 1, 'class_id' => 1]);
        $quiz = Quiz::factory()->create(['level_id' => 1, 'is_active' => true]);
        
        QuizAttempt::factory()->create([
            'student_id' => $student->student_id,
            'quiz_id' => $quiz->quiz_id,
            'score' => 80,
            'submitted_at' => Carbon::now(),
        ]);
        
        QuizAttempt::factory()->create([
            'student_id' => $student->student_id,
            'quiz_id' => $quiz->quiz_id,
            'score' => 90,
            'submitted_at' => Carbon::now(),
        ]);

        $lesson = Lesson::factory()->create(['level_id' => 1]);
        StudentLessonProgress::factory()->create([
            'student_id' => $student->student_id,
            'lesson_id' => $lesson->lesson_id,
        ]);

        // Act
        $features = $this->service->calculateStudentFeatures($student->student_id);

        // Assert
        $this->assertNotNull($features);
        $this->assertEquals(85.0, $features['avg_quiz_score']); // (80 + 90) / 2
    }

    /** @test */
    public function it_returns_zero_quiz_score_when_no_quizzes_exist()
    {
        // Arrange
        $student = Student::factory()->create(['class_id' => 1]);
        $level = Level::factory()->create(['level_id' => 1, 'class_id' => 1]);
        $lesson = Lesson::factory()->create(['level_id' => 1]);
        
        StudentLessonProgress::factory()->create([
            'student_id' => $student->student_id,
            'lesson_id' => $lesson->lesson_id,
        ]);

        // Act
        $features = $this->service->calculateStudentFeatures($student->student_id);

        // Assert
        $this->assertNotNull($features);
        $this->assertEquals(0, $features['avg_quiz_score']);
    }

    /** @test */
    public function it_predicts_risk_successfully()
    {
        // Arrange
        Http::fake([
            $this->apiUrl . '/predict' => Http::response([
                'success' => true,
                'risk_level' => 2,
                'risk_label' => 'Medium',
                'confidence' => 0.85,
            ], 200),
        ]);

        $student = Student::factory()->create(['class_id' => 1]);
        $level = Level::factory()->create(['level_id' => 1, 'class_id' => 1]);
        $lesson = Lesson::factory()->create(['level_id' => 1]);
        
        StudentLessonProgress::factory()->create([
            'student_id' => $student->student_id,
            'lesson_id' => $lesson->lesson_id,
        ]);

        // Act
        $result = $this->service->predictRisk($student->student_id);

        // Assert
        $this->assertNotNull($result);
        $this->assertTrue($result['success']);
        $this->assertEquals(2, $result['risk_level']);
        $this->assertEquals('Medium', $result['risk_label']);
        $this->assertEquals(0.85, $result['confidence']);
        
        // Check that prediction was saved
        $prediction = StudentRiskPrediction::where('student_id', $student->student_id)->first();
        $this->assertNotNull($prediction);
        $this->assertEquals(2, $prediction->risk_level);
        $this->assertEquals(85, $prediction->confidence); // Converted to percentage
    }

    /** @test */
    public function it_returns_null_when_api_returns_error()
    {
        // Arrange
        Http::fake([
            $this->apiUrl . '/predict' => Http::response([
                'success' => false,
                'error' => 'Invalid features',
            ], 200),
        ]);

        $student = Student::factory()->create(['class_id' => 1]);
        $level = Level::factory()->create(['level_id' => 1, 'class_id' => 1]);
        $lesson = Lesson::factory()->create(['level_id' => 1]);
        
        StudentLessonProgress::factory()->create([
            'student_id' => $student->student_id,
            'lesson_id' => $lesson->lesson_id,
        ]);

        // Act
        $result = $this->service->predictRisk($student->student_id);

        // Assert
        $this->assertNull($result);
    }

    /** @test */
    public function it_handles_api_connection_exception()
    {
        // Arrange
        Http::fake(function () {
            throw new \Illuminate\Http\Client\ConnectionException('Connection refused');
        });

        $student = Student::factory()->create(['class_id' => 1]);
        $level = Level::factory()->create(['level_id' => 1, 'class_id' => 1]);
        $lesson = Lesson::factory()->create(['level_id' => 1]);
        
        StudentLessonProgress::factory()->create([
            'student_id' => $student->student_id,
            'lesson_id' => $lesson->lesson_id,
        ]);

        // Act
        $result = $this->service->predictRisk($student->student_id);

        // Assert
        $this->assertNull($result);
    }

    /** @test */
    public function it_returns_null_when_student_has_no_features()
    {
        // Arrange
        $student = Student::factory()->create(['class_id' => null]);

        // Act
        $result = $this->service->predictRisk($student->student_id);

        // Assert
        $this->assertNull($result);
    }

    /** @test */
    public function it_checks_api_availability()
    {
        // Arrange
        Http::fake([
            $this->apiUrl . '/health' => Http::response([], 200),
        ]);

        // Act
        $isAvailable = $this->service->isApiAvailable();

        // Assert
        $this->assertTrue($isAvailable);
    }

    /** @test */
    public function it_returns_false_when_api_is_unavailable()
    {
        // Arrange
        Http::fake(function () {
            throw new \Exception('Connection failed');
        });

        // Act
        $isAvailable = $this->service->isApiAvailable();

        // Assert
        $this->assertFalse($isAvailable);
    }

    /** @test */
    public function it_predicts_for_entire_class()
    {
        // Arrange
        Http::fake([
            $this->apiUrl . '/health' => Http::response([], 200),
            $this->apiUrl . '/predict' => Http::response([
                'success' => true,
                'risk_level' => 1,
                'risk_label' => 'Low',
                'confidence' => 0.9,
            ], 200),
        ]);

        $classId = 1;
        $level = Level::factory()->create(['level_id' => 1, 'class_id' => $classId]);
        $lesson = Lesson::factory()->create(['level_id' => 1]);
        
        $student1 = Student::factory()->create(['class_id' => $classId]);
        $student1->user()->associate(\App\Models\User::factory()->create(['role' => 'student']));
        $student1->save();
        
        StudentLessonProgress::factory()->create([
            'student_id' => $student1->student_id,
            'lesson_id' => $lesson->lesson_id,
        ]);

        // Act
        $result = $this->service->predictForClass($classId);

        // Assert
        $this->assertArrayHasKey('predictions', $result);
        $this->assertArrayHasKey('errors', $result);
        $this->assertTrue($result['api_available']);
        $this->assertCount(1, $result['predictions']);
    }

    /** @test */
    public function it_handles_class_prediction_when_api_unavailable()
    {
        // Arrange
        Http::fake(function () {
            throw new \Exception('Connection failed');
        });

        // Act
        $result = $this->service->predictForClass(1);

        // Assert
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('api_unavailable', $result['error']);
        $this->assertArrayHasKey('predictions', $result);
    }

    /** @test */
    public function it_gets_latest_prediction_for_student()
    {
        // Arrange
        $student = Student::factory()->create();
        
        $prediction1 = StudentRiskPrediction::factory()->create([
            'student_id' => $student->student_id,
            'predicted_at' => Carbon::now()->subDays(2),
        ]);
        
        $prediction2 = StudentRiskPrediction::factory()->create([
            'student_id' => $student->student_id,
            'predicted_at' => Carbon::now()->subDays(1),
        ]);

        // Act
        $latest = $this->service->getLatestPrediction($student->student_id);

        // Assert
        $this->assertNotNull($latest);
        $this->assertEquals($prediction2->prediction_id, $latest->prediction_id);
    }

    /** @test */
    public function it_handles_students_without_user_in_class_prediction()
    {
        // Arrange
        Http::fake([
            $this->apiUrl . '/health' => Http::response([], 200),
        ]);

        $classId = 1;
        $student = Student::factory()->create(['class_id' => $classId]);
        // Student without user relationship

        // Act
        $result = $this->service->predictForClass($classId);

        // Assert
        $this->assertArrayHasKey('predictions', $result);
        $this->assertArrayHasKey('errors', $result);
    }

    /** @test */
    public function it_rounds_feature_values_correctly()
    {
        // Arrange
        $student = Student::factory()->create(['class_id' => 1]);
        $level = Level::factory()->create(['level_id' => 1, 'class_id' => 1]);
        $lesson = Lesson::factory()->create(['level_id' => 1]);
        
        StudentLessonProgress::factory()->create([
            'student_id' => $student->student_id,
            'lesson_id' => $lesson->lesson_id,
            'watched_percentage' => 33.333,
        ]);

        // Act
        $features = $this->service->calculateStudentFeatures($student->student_id);

        // Assert
        $this->assertNotNull($features);
        $this->assertEquals(33.33, $features['avg_watch_pct']); // Rounded to 2 decimals
        $this->assertEquals(0.0, $features['completion_rate']); // Rounded
    }
}
