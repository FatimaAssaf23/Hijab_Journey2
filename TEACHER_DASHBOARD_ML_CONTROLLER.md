# Teacher Dashboard ML Controller

## Overview

The `TeacherDashboardController` provides endpoints for teachers to view and manage student risk predictions using the ML Prediction Service.

## Created Files

1. **`app/Http/Controllers/TeacherDashboardController.php`** - Main controller with three methods:
   - `index()` - Display dashboard with risk predictions
   - `getStudentRisk($studentId)` - Get prediction for specific student (AJAX)
   - `refreshPredictions($classId)` - Refresh predictions for a class (AJAX)

## Routes Added

Routes have been added to `routes/web.php` in the teacher routes group:

```php
// ML Prediction routes
Route::get('/teacher/dashboard/ml', [TeacherDashboardController::class, 'index'])
    ->name('teacher.dashboard.ml');
Route::get('/teacher/students/{studentId}/risk', [TeacherDashboardController::class, 'getStudentRisk'])
    ->name('teacher.students.risk');
Route::post('/teacher/classes/{classId}/refresh-predictions', [TeacherDashboardController::class, 'refreshPredictions'])
    ->name('teacher.classes.refresh-predictions');
```

## Features

### 1. Authorization
- All methods check if user is a teacher
- Verifies teacher has access to the requested class/student
- Returns appropriate error responses for unauthorized access

### 2. Dashboard View (`index()`)
- Gets all classes taught by the teacher
- Loads students with proper filtering (only students with role 'student')
- Generates risk predictions for the first class
- Returns view with classes, selected class, and predictions

### 3. Student Risk Endpoint (`getStudentRisk()`)
- AJAX endpoint for getting individual student predictions
- Validates student belongs to teacher's class
- Returns JSON response with prediction data

### 4. Refresh Predictions (`refreshPredictions()`)
- AJAX endpoint to refresh predictions for an entire class
- Validates teacher has access to the class
- Returns updated predictions as JSON

## Usage Examples

### In Blade View

```blade
<!-- Display predictions in dashboard -->
@foreach($predictions as $prediction)
    <div class="risk-card">
        <h3>{{ $prediction['student_name'] }}</h3>
        <span class="badge badge-{{ $prediction['risk_level'] == 0 ? 'success' : ($prediction['risk_level'] == 1 ? 'warning' : 'danger') }}">
            {{ $prediction['risk_label'] }}
        </span>
        <p>Confidence: {{ number_format($prediction['confidence'] * 100, 1) }}%</p>
    </div>
@endforeach
```

### AJAX Call to Get Student Risk

```javascript
// Get risk prediction for a student
fetch('/teacher/students/1/risk')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Risk Level:', data.prediction.risk_label);
            console.log('Confidence:', data.prediction.confidence);
        }
    });
```

### AJAX Call to Refresh Predictions

```javascript
// Refresh predictions for a class
fetch('/teacher/classes/1/refresh-predictions', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('Updated predictions:', data.predictions);
        // Update UI with new predictions
    }
});
```

## Response Formats

### Success Response (getStudentRisk)

```json
{
    "success": true,
    "prediction": {
        "risk_level": 1,
        "risk_label": "May Struggle",
        "confidence": 0.943,
        "probabilities": {
            "will_pass": 0.05,
            "may_struggle": 0.943,
            "needs_help": 0.007
        }
    }
}
```

### Success Response (refreshPredictions)

```json
{
    "success": true,
    "predictions": [
        {
            "student_id": 1,
            "student_name": "John Doe",
            "risk_level": 0,
            "risk_label": "Will Pass",
            "confidence": 0.95
        },
        {
            "student_id": 2,
            "student_name": "Jane Smith",
            "risk_level": 1,
            "risk_label": "May Struggle",
            "confidence": 0.87
        }
    ]
}
```

### Error Response

```json
{
    "success": false,
    "message": "Student not found."
}
```

## Integration with Existing Dashboard

**Note:** There's already a teacher dashboard route at `/teacher/dashboard` defined as a closure in `routes/web.php`. 

You have two options:

### Option 1: Replace Existing Route
Replace the closure route with the controller:

```php
// Replace this:
Route::get('/teacher/dashboard', function () { ... });

// With this:
Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'can:isTeacher'])
    ->name('teacher.dashboard');
```

### Option 2: Use Separate Route
Keep both routes and use the new one for ML predictions:
- Existing: `/teacher/dashboard` - Original dashboard
- New: `/teacher/dashboard/ml` - ML-enhanced dashboard

## View File

You'll need to create or update `resources/views/teacher/dashboard.blade.php` to display the predictions. Example structure:

```blade
@extends('layouts.teacher')

@section('content')
    <div class="container">
        <h1>Teacher Dashboard</h1>
        
        @if($class)
            <h2>Class: {{ $class->class_name }}</h2>
            
            <div class="predictions-grid">
                @forelse($predictions as $prediction)
                    <div class="prediction-card">
                        <h3>{{ $prediction['student_name'] }}</h3>
                        <div class="risk-badge risk-{{ $prediction['risk_level'] }}">
                            {{ $prediction['risk_label'] }}
                        </div>
                        <p>Confidence: {{ number_format($prediction['confidence'] * 100, 1) }}%</p>
                    </div>
                @empty
                    <p>No predictions available. Students may not have enough data.</p>
                @endforelse
            </div>
        @else
            <p>You are not assigned to any class.</p>
        @endif
    </div>
@endsection
```

## Security Features

1. **Role Verification**: Checks user role is 'teacher'
2. **Class Ownership**: Verifies teacher owns the requested class
3. **Student Access**: Ensures student belongs to teacher's class
4. **Proper Error Handling**: Returns appropriate HTTP status codes

## Dependencies

- `MLPredictionService` - Must be properly configured
- Python ML API - Must be running (see `ml_api/README.md`)
- Database migration - `student_risk_predictions` table must exist

## Testing

Test the endpoints:

```bash
# Test dashboard (requires authentication)
curl -X GET http://your-app.test/teacher/dashboard/ml \
  -H "Cookie: laravel_session=..."

# Test student risk (requires authentication)
curl -X GET http://your-app.test/teacher/students/1/risk \
  -H "Cookie: laravel_session=..."

# Test refresh predictions (requires authentication)
curl -X POST http://your-app.test/teacher/classes/1/refresh-predictions \
  -H "Cookie: laravel_session=..." \
  -H "X-CSRF-TOKEN: ..."
```

## Next Steps

1. Create or update the `teacher/dashboard.blade.php` view
2. Add JavaScript for AJAX calls if needed
3. Style the risk prediction cards
4. Consider adding filters (by risk level, class, etc.)
5. Add real-time updates if desired
