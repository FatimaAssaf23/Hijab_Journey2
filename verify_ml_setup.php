<?php

/**
 * Comprehensive ML Setup Verification Script
 * Run: php verify_ml_setup.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "═══════════════════════════════════════════════════════════════\n";
echo "  ML Prediction System - Complete Setup Verification\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$allGood = true;

// 1. Check Configuration
echo "1. Configuration Check:\n";
echo "   ───────────────────────────────────────────────────────────\n";
$apiUrl = config('services.ml_api.url', 'NOT SET');
if ($apiUrl === 'NOT SET') {
    echo "   ❌ ML_API_URL not configured in .env\n";
    echo "      Add to .env: ML_API_URL=http://localhost:5000\n";
    $allGood = false;
} else {
    echo "   ✅ ML_API_URL: {$apiUrl}\n";
}
echo "\n";

// 2. Check Model File
echo "2. Model File Check:\n";
echo "   ───────────────────────────────────────────────────────────\n";
$modelPath = __DIR__ . '/ml_api/student_risk_model.pkl';
if (file_exists($modelPath)) {
    $size = filesize($modelPath);
    echo "   ✅ Model file exists: ml_api/student_risk_model.pkl ({$size} bytes)\n";
} else {
    echo "   ❌ Model file missing: ml_api/student_risk_model.pkl\n";
    echo "      Run: cd ml_api && python create_mock_model.py\n";
    $allGood = false;
}
echo "\n";

// 3. Check Database Migration
echo "3. Database Migration Check:\n";
echo "   ───────────────────────────────────────────────────────────\n";
try {
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('student_risk_predictions');
    if ($tableExists) {
        $count = \App\Models\StudentRiskPrediction::count();
        echo "   ✅ Table 'student_risk_predictions' exists ({$count} records)\n";
    } else {
        echo "   ❌ Table 'student_risk_predictions' does not exist\n";
        echo "      Run: php artisan migrate\n";
        $allGood = false;
    }
} catch (\Exception $e) {
    echo "   ⚠️  Could not check database: " . $e->getMessage() . "\n";
}
echo "\n";

// 4. Check Service Class
echo "4. Service Class Check:\n";
echo "   ───────────────────────────────────────────────────────────\n";
if (class_exists(\App\Services\MLPredictionService::class)) {
    echo "   ✅ MLPredictionService class exists\n";
} else {
    echo "   ❌ MLPredictionService class not found\n";
    $allGood = false;
}
echo "\n";

// 5. Check Controllers
echo "5. Controllers Check:\n";
echo "   ───────────────────────────────────────────────────────────\n";
$controllers = [
    'TeacherDashboardController' => \App\Http\Controllers\TeacherDashboardController::class,
    'MLPredictionTestController' => \App\Http\Controllers\MLPredictionTestController::class,
    'MLDiagnosticController' => \App\Http\Controllers\MLDiagnosticController::class,
];

foreach ($controllers as $name => $class) {
    if (class_exists($class)) {
        echo "   ✅ {$name} exists\n";
    } else {
        echo "   ❌ {$name} not found\n";
        $allGood = false;
    }
}
echo "\n";

// 6. Check Observers
echo "6. Observers Check:\n";
echo "   ───────────────────────────────────────────────────────────\n";
$observers = [
    'StudentLessonProgressObserver' => \App\Observers\StudentLessonProgressObserver::class,
    'QuizAttemptObserver' => \App\Observers\QuizAttemptObserver::class,
];

foreach ($observers as $name => $class) {
    if (class_exists($class)) {
        echo "   ✅ {$name} exists\n";
    } else {
        echo "   ❌ {$name} not found\n";
        $allGood = false;
    }
}
echo "\n";

// 7. Check Models
echo "7. Models Check:\n";
echo "   ───────────────────────────────────────────────────────────\n";
if (class_exists(\App\Models\StudentRiskPrediction::class)) {
    echo "   ✅ StudentRiskPrediction model exists\n";
} else {
    echo "   ❌ StudentRiskPrediction model not found\n";
    $allGood = false;
}
echo "\n";

// 8. Check ML API Connection
echo "8. ML API Connection Check:\n";
echo "   ───────────────────────────────────────────────────────────\n";
try {
    $response = \Illuminate\Support\Facades\Http::timeout(3)
        ->get($apiUrl . '/health');
    
    if ($response->successful()) {
        $data = $response->json();
        echo "   ✅ ML API is running\n";
        echo "      Status: " . ($data['status'] ?? 'unknown') . "\n";
        echo "      Model loaded: " . ($data['model_loaded'] ? 'Yes' : 'No') . "\n";
    } else {
        echo "   ⚠️  ML API returned error: HTTP {$response->status()}\n";
        $allGood = false;
    }
} catch (\Exception $e) {
    echo "   ❌ ML API is NOT running\n";
    echo "      Error: {$e->getMessage()}\n";
    echo "      Action: Start the API with: cd ml_api && python app.py\n";
    $allGood = false;
}
echo "\n";

// 9. Check Routes
echo "9. Routes Check:\n";
echo "   ───────────────────────────────────────────────────────────\n";
$routes = [
    'teacher.dashboard' => '/teacher/dashboard',
    'teacher.student.risk' => '/teacher/student/{id}/risk',
    'teacher.class.refresh' => '/teacher/class/{id}/refresh-predictions',
    'test.ml.api' => '/test-ml-api-connection',
    'ml.diagnose' => '/ml-diagnose/{classId?}',
];

foreach ($routes as $name => $path) {
    try {
        $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName($name);
        if ($route) {
            echo "   ✅ Route '{$name}' exists\n";
        } else {
            echo "   ⚠️  Route '{$name}' not found\n";
        }
    } catch (\Exception $e) {
        echo "   ⚠️  Could not verify route '{$name}'\n";
    }
}
echo "\n";

// Summary
echo "═══════════════════════════════════════════════════════════════\n";
if ($allGood) {
    echo "✅ All checks passed! System is ready.\n";
    echo "\nNext steps:\n";
    echo "1. Start ML API: cd ml_api && python app.py\n";
    echo "2. Visit teacher dashboard: /teacher/dashboard\n";
    echo "3. Test connection: /test-ml-api-connection\n";
} else {
    echo "⚠️  Some issues found. Please fix them above.\n";
}
echo "═══════════════════════════════════════════════════════════════\n";
