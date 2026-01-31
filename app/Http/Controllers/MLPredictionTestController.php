<?php

namespace App\Http\Controllers;

use App\Services\MLPredictionService;
use Illuminate\Http\Request;

/**
 * Test controller for ML Prediction Service
 * This is a temporary controller for testing purposes
 * You can remove it or integrate into your existing controllers
 */
class MLPredictionTestController extends Controller
{
    protected $mlService;

    public function __construct(MLPredictionService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Test prediction for a student
     * Usage: /test-ml-prediction/{studentId}
     */
    public function testPrediction($studentId)
    {
        try {
            $prediction = $this->mlService->predictRisk($studentId);

            if ($prediction) {
                return response()->json([
                    'success' => true,
                    'message' => 'Prediction generated successfully',
                    'data' => [
                        'student_id' => $studentId,
                        'risk_level' => $prediction['risk_level'],
                        'risk_label' => $prediction['risk_label'],
                        'confidence' => round($prediction['confidence'] * 100, 2) . '%',
                        'probabilities' => [
                            'will_pass' => round($prediction['probabilities']['will_pass'] * 100, 2) . '%',
                            'may_struggle' => round($prediction['probabilities']['may_struggle'] * 100, 2) . '%',
                            'needs_help' => round($prediction['probabilities']['needs_help'] * 100, 2) . '%',
                        ]
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to generate prediction. Student may not have enough data or API is not available.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test API connection
     * Usage: /test-ml-api-connection
     */
    public function testApiConnection()
    {
        $apiUrl = config('services.ml_api.url');
        
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)
                ->get($apiUrl . '/health');
            
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ML API is connected and running',
                    'api_url' => $apiUrl,
                    'api_response' => $response->json()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'ML API returned error status: ' . $response->status(),
                    'api_url' => $apiUrl
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot connect to ML API: ' . $e->getMessage(),
                'api_url' => $apiUrl,
                'hint' => 'Make sure the Python ML API is running on ' . $apiUrl
            ], 500);
        }
    }

    /**
     * Get student features (without API call)
     * Usage: /test-ml-features/{studentId}
     */
    public function testFeatures($studentId)
    {
        try {
            $features = $this->mlService->calculateStudentFeatures($studentId);

            if ($features) {
                return response()->json([
                    'success' => true,
                    'message' => 'Features calculated successfully',
                    'data' => $features
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to calculate features. Student may not have progress data.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
