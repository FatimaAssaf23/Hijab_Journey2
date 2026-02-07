<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MeetingEnrollment;
use App\Services\AttendanceTrackingService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkForPrompt($enrollmentId)
    {
        $enrollment = MeetingEnrollment::where('student_id', auth()->id())
            ->findOrFail($enrollmentId);
        
        $service = new AttendanceTrackingService();
        $prompt = $service->shouldShowPrompt($enrollment);
        
        return response()->json([
            'prompt_needed' => $prompt !== null,
            'confirmation_id' => $prompt?->id,
        ]);
    }
}
