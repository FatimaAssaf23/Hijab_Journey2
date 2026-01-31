<?php

namespace App\Http\Controllers;

use App\Services\MLPredictionService;
use App\Models\Student;
use App\Models\StudentLessonProgress;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Diagnostic controller to help debug ML prediction issues
 * Remove or protect with authentication in production
 */
class MLDiagnosticController extends Controller
{
    protected $mlService;

    public function __construct(MLPredictionService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Diagnostic page to check why predictions aren't working
     */
    public function diagnose($classId = null)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get class
        if (!$classId) {
            $class = \App\Models\StudentClass::where('teacher_id', $user->user_id)->first();
            $classId = $class ? $class->class_id : null;
        } else {
            $class = \App\Models\StudentClass::find($classId);
        }

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        $students = Student::where('class_id', $classId)
            ->with('user')
            ->get();

        $diagnostics = [];

        foreach ($students as $student) {
            $diag = [
                'student_id' => $student->student_id,
                'student_name' => $student->user->name ?? 'Unknown',
                'has_progress' => false,
                'progress_count' => 0,
                'current_level' => null,
                'features' => null,
                'api_status' => 'not_tested',
                'prediction' => null,
                'errors' => []
            ];

            // Check for lesson progress
            $allProgress = StudentLessonProgress::where('student_id', $student->student_id)->get();
            $diag['progress_count'] = $allProgress->count();
            $diag['has_progress'] = $allProgress->count() > 0;

            // Get current level
            $currentLevel = $this->mlService->calculateStudentFeatures($student->student_id);
            if ($currentLevel) {
                $diag['current_level'] = 'Found';
            } else {
                $diag['errors'][] = 'No current level or no progress data';
            }

            // Try to calculate features
            $features = $this->mlService->calculateStudentFeatures($student->student_id);
            if ($features) {
                $diag['features'] = $features;
            } else {
                $diag['errors'][] = 'Cannot calculate features - student may not have lesson progress';
            }

            // Test API connection
            try {
                $apiUrl = config('services.ml_api.url');
                $response = \Illuminate\Support\Facades\Http::timeout(5)->get($apiUrl . '/health');
                if ($response->successful()) {
                    $diag['api_status'] = 'connected';
                    $diag['api_response'] = $response->json();
                } else {
                    $diag['api_status'] = 'error';
                    $diag['errors'][] = 'API returned status: ' . $response->status();
                }
            } catch (\Exception $e) {
                $diag['api_status'] = 'connection_failed';
                $diag['errors'][] = 'Cannot connect to ML API: ' . $e->getMessage();
            }

            // Try prediction if features exist
            if ($features) {
                $prediction = $this->mlService->predictRisk($student->student_id);
                if ($prediction) {
                    $diag['prediction'] = $prediction;
                } else {
                    $diag['errors'][] = 'Prediction failed - check logs';
                }
            }

            $diagnostics[] = $diag;
        }

        return response()->json([
            'class_id' => $classId,
            'class_name' => $class->class_name,
            'ml_api_url' => config('services.ml_api.url'),
            'students' => $diagnostics
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
