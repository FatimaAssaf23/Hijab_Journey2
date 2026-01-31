# ML Prediction Service Integration Guide

This guide explains how to use the ML Prediction Service to get student risk predictions from the Python Flask API.

## Setup

### 1. Configure Environment Variable

Add the following to your `.env` file:

```env
ML_API_URL=http://localhost:5000
```

### 2. Run Database Migration

```bash
php artisan migrate
```

This will create the `student_risk_predictions` table.

### 3. Start Python ML API

Make sure the Python Flask API is running (see `ml_api/README.md` for details):

```bash
cd ml_api
python app.py
```

## Usage Examples

### Basic Usage - Get Prediction for a Single Student

```php
use App\Services\MLPredictionService;

$service = new MLPredictionService();
$studentId = 1; // Student ID

// Get risk prediction
$prediction = $service->predictRisk($studentId);

if ($prediction) {
    echo "Risk Level: " . $prediction['risk_level'] . "\n";
    echo "Risk Label: " . $prediction['risk_label'] . "\n";
    echo "Confidence: " . ($prediction['confidence'] * 100) . "%\n";
    
    // Probabilities for each risk category
    echo "Will Pass: " . ($prediction['probabilities']['will_pass'] * 100) . "%\n";
    echo "May Struggle: " . ($prediction['probabilities']['may_struggle'] * 100) . "%\n";
    echo "Needs Help: " . ($prediction['probabilities']['needs_help'] * 100) . "%\n";
}
```

### Get Predictions for Entire Class

```php
use App\Services\MLPredictionService;

$service = new MLPredictionService();
$classId = 1; // Class ID

$predictions = $service->predictForClass($classId);

foreach ($predictions as $prediction) {
    echo "Student: " . $prediction['student_name'] . "\n";
    echo "Risk: " . $prediction['risk_label'] . "\n";
    echo "Confidence: " . ($prediction['confidence'] * 100) . "%\n\n";
}
```

### Get Latest Prediction from Database

```php
use App\Services\MLPredictionService;

$service = new MLPredictionService();
$studentId = 1;

$latestPrediction = $service->getLatestPrediction($studentId);

if ($latestPrediction) {
    echo "Risk Level: " . $latestPrediction->risk_level . "\n";
    echo "Risk Label: " . $latestPrediction->risk_label . "\n";
    echo "Confidence: " . $latestPrediction->confidence . "%\n";
    echo "Predicted At: " . $latestPrediction->predicted_at . "\n";
}
```

### Calculate Student Features Only (Without API Call)

```php
use App\Services\MLPredictionService;

$service = new MLPredictionService();
$studentId = 1;

$features = $service->calculateStudentFeatures($studentId);

if ($features) {
    echo "Average Watch %: " . $features['avg_watch_pct'] . "%\n";
    echo "Completion Rate: " . ($features['completion_rate'] * 100) . "%\n";
    echo "Average Quiz Score: " . $features['avg_quiz_score'] . "\n";
    echo "Days Inactive: " . $features['days_inactive'] . "\n";
    echo "Lessons Completed: " . $features['lessons_completed'] . "\n";
}
```

## Using in Controllers

### Example Controller Method

```php
use App\Services\MLPredictionService;
use Illuminate\Http\Request;

public function getStudentRiskPrediction(Request $request, $studentId)
{
    $service = new MLPredictionService();
    $prediction = $service->predictRisk($studentId);
    
    if (!$prediction) {
        return response()->json([
            'success' => false,
            'message' => 'Unable to generate prediction. Student may not have enough data.'
        ], 404);
    }
    
    return response()->json([
        'success' => true,
        'data' => $prediction
    ]);
}
```

### Example: Dashboard with Risk Predictions

```php
use App\Services\MLPredictionService;

public function dashboard()
{
    $user = Auth::user();
    $student = $user->student;
    
    if (!$student) {
        return redirect()->route('home');
    }
    
    $service = new MLPredictionService();
    
    // Get latest prediction (from database, no API call)
    $latestPrediction = $service->getLatestPrediction($student->student_id);
    
    // Or get fresh prediction (calls API)
    // $prediction = $service->predictRisk($student->student_id);
    
    return view('student.dashboard', [
        'student' => $student,
        'riskPrediction' => $latestPrediction
    ]);
}
```

## Response Format

### Successful Prediction Response

```json
{
    "success": true,
    "risk_level": 1,
    "risk_label": "May Struggle",
    "confidence": 0.943,
    "probabilities": {
        "will_pass": 0.05,
        "may_struggle": 0.943,
        "needs_help": 0.007
    }
}
```

### Risk Levels

- **0 - Will Pass**: Student is performing well and likely to succeed
- **1 - May Struggle**: Student may need some support
- **2 - Needs Help**: Student requires immediate intervention

## Database Model

The `StudentRiskPrediction` model provides access to stored predictions:

```php
use App\Models\StudentRiskPrediction;

// Get all predictions for a student
$predictions = StudentRiskPrediction::where('student_id', $studentId)
    ->orderBy('predicted_at', 'desc')
    ->get();

// Get latest prediction
$latest = StudentRiskPrediction::where('student_id', $studentId)
    ->latest('predicted_at')
    ->first();

// Access relationships
$student = $latest->student;
$level = $latest->level;

// Get badge color for UI
$badgeColor = $latest->risk_badge_color; // 'success', 'warning', or 'danger'
```

## Error Handling

The service handles errors gracefully:

- Returns `null` if student has no progress data
- Returns `null` if API call fails
- Logs errors to Laravel log file
- Saves predictions only on successful API response

Check logs for detailed error messages:

```bash
tail -f storage/logs/laravel.log
```

## Scheduling Predictions

You can schedule predictions to run automatically using Laravel's task scheduler:

```php
// In app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
    // Run predictions daily at 2 AM
    $schedule->call(function () {
        $service = new MLPredictionService();
        
        // Get all active students
        $students = Student::all();
        
        foreach ($students as $student) {
            $service->predictRisk($student->student_id);
        }
    })->dailyAt('02:00');
}
```

## Troubleshooting

### API Connection Issues

1. **Check if Python API is running:**
   ```bash
   curl http://localhost:5000/health
   ```

2. **Check .env configuration:**
   ```env
   ML_API_URL=http://localhost:5000
   ```

3. **Check Laravel logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

### No Prediction Data

- Ensure student has lesson progress in at least one level
- Check that student has a current level assigned
- Verify that `StudentLessonProgress` records exist

### Migration Issues

If the migration fails, ensure:
- Students table exists
- Levels table exists
- Foreign key constraints are correct
