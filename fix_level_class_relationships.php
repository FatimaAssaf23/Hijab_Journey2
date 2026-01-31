<?php

/**
 * Fix Level-Class Relationships
 * Assigns levels to Class 1 based on student progress
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Level;
use App\Models\Student;
use App\Models\StudentLessonProgress;

echo "=== Fixing Level-Class Relationships ===\n\n";

// Get Class 1
$classId = 1;

// Find all levels that students in class 1 have progress in
$students = Student::where('class_id', $classId)->pluck('student_id');

if ($students->isEmpty()) {
    echo "No students found in class {$classId}\n";
    exit(1);
}

echo "Found {$students->count()} students in class {$classId}\n";

// Get all levels from student progress
$levelIds = StudentLessonProgress::whereIn('student_id', $students)
    ->whereHas('lesson', function($query) {
        $query->whereNotNull('level_id');
    })
    ->with('lesson')
    ->get()
    ->map(function($progress) {
        return $progress->lesson ? $progress->lesson->level_id : null;
    })
    ->filter()
    ->unique()
    ->values();

echo "Found {$levelIds->count()} unique levels from student progress\n\n";

// Update levels to belong to class 1
$updated = 0;
foreach ($levelIds as $levelId) {
    $level = Level::find($levelId);
    if ($level) {
        $oldClassId = $level->class_id ?? 'null';
        $level->class_id = $classId;
        $level->save();
        echo "Updated Level {$levelId} ({$level->level_name}): class_id {$oldClassId} -> {$classId}\n";
        $updated++;
    }
}

echo "\n=== Summary ===\n";
echo "Updated {$updated} levels to belong to class {$classId}\n";
echo "\nYou can now refresh the teacher dashboard to see predictions!\n";
