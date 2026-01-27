<?php

namespace App\Services;

use App\Models\ScheduleEvent;
use App\Models\StudentClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScheduleService
{
    /**
     * Default schedule template configuration
     * This defines the default schedule pattern for teachers
     */
    protected $defaultScheduleTemplate = [
        'days' => [1, 2, 3, 4, 5], // Monday to Friday (1 = Monday, 7 = Sunday)
        'shift_from' => '09:00', // Default start time
        'shift_to' => '17:00', // Default end time
        'event_type' => 'lesson',
        'color' => '#3B82F6', // Blue color for auto-generated schedules
        'weeks_ahead' => 8, // Generate schedule for 8 weeks ahead
    ];

    /**
     * Generate default schedule for a teacher when assigned to a class
     * 
     * @param int $teacherId
     * @param int|null $classId
     * @param array $customTemplate Optional custom template to override defaults
     * @return array Array of created schedule events
     */
    public function generateDefaultScheduleForTeacher($teacherId, $classId = null, array $customTemplate = [])
    {
        $template = array_merge($this->defaultScheduleTemplate, $customTemplate);
        
        // Check if teacher already has auto-generated schedules for this class
        $existingSchedules = ScheduleEvent::where('teacher_id', $teacherId)
            ->where('is_auto_generated', true)
            ->when($classId, function($query) use ($classId) {
                return $query->where('class_id', $classId);
            })
            ->count();

        // If schedules already exist, skip generation (to avoid duplicates)
        if ($existingSchedules > 0) {
            Log::info("Teacher {$teacherId} already has auto-generated schedules. Skipping generation.");
            return [];
        }

        $createdEvents = [];
        $startDate = Carbon::now()->startOfWeek(); // Start from beginning of current week
        $endDate = Carbon::now()->addWeeks($template['weeks_ahead']);

        // Get class name for event title if class_id is provided
        $className = null;
        if ($classId) {
            $class = StudentClass::find($classId);
            $className = $class ? $class->class_name : null;
        }

        // Generate schedule for each day in the template
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dayOfWeek = $currentDate->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.
            // Convert to ISO format (1 = Monday, 7 = Sunday)
            $isoDayOfWeek = $dayOfWeek == 0 ? 7 : $dayOfWeek;

            if (in_array($isoDayOfWeek, $template['days'])) {
                // Create schedule event for this day
                $eventTitle = $className 
                    ? "Class: {$className}" 
                    : "Teaching Schedule";

                // Parse times to ensure proper format
                $shiftFrom = Carbon::parse($template['shift_from'])->format('H:i:s');
                $shiftTo = Carbon::parse($template['shift_to'])->format('H:i:s');
                
                $event = ScheduleEvent::create([
                    'title' => $eventTitle,
                    'description' => $className 
                        ? "Regular teaching schedule for {$className}" 
                        : "Regular teaching schedule",
                    'event_date' => $currentDate->toDateString(),
                    'event_time' => $shiftFrom,
                    'shift_from' => $shiftFrom,
                    'shift_to' => $shiftTo,
                    'event_type' => $template['event_type'],
                    'color' => $template['color'],
                    'is_active' => true,
                    'is_auto_generated' => true,
                    'teacher_id' => $teacherId,
                    'class_id' => $classId,
                ]);

                $createdEvents[] = $event;
            }

            $currentDate->addDay();
        }

        Log::info("Generated " . count($createdEvents) . " default schedule events for teacher {$teacherId}" . ($classId ? " and class {$classId}" : ""));

        return $createdEvents;
    }

    /**
     * Regenerate schedule for a teacher (delete old auto-generated and create new)
     * 
     * @param int $teacherId
     * @param int|null $classId
     * @param array $customTemplate
     * @return array Array of created schedule events
     */
    public function regenerateScheduleForTeacher($teacherId, $classId = null, array $customTemplate = [])
    {
        // Delete existing auto-generated schedules
        ScheduleEvent::where('teacher_id', $teacherId)
            ->where('is_auto_generated', true)
            ->when($classId, function($query) use ($classId) {
                return $query->where('class_id', $classId);
            })
            ->delete();

        // Generate new schedules
        return $this->generateDefaultScheduleForTeacher($teacherId, $classId, $customTemplate);
    }

    /**
     * Get default schedule template
     * 
     * @return array
     */
    public function getDefaultTemplate()
    {
        return $this->defaultScheduleTemplate;
    }

    /**
     * Set custom default schedule template
     * 
     * @param array $template
     * @return void
     */
    public function setDefaultTemplate(array $template)
    {
        $this->defaultScheduleTemplate = array_merge($this->defaultScheduleTemplate, $template);
    }

    /**
     * Check for schedule conflicts
     * 
     * @param int $teacherId
     * @param string $date
     * @param string $shiftFrom
     * @param string $shiftTo
     * @param int|null $excludeEventId Event ID to exclude from conflict check
     * @return bool True if conflict exists
     */
    public function hasConflict($teacherId, $date, $shiftFrom, $shiftTo, $excludeEventId = null)
    {
        $query = ScheduleEvent::where('teacher_id', $teacherId)
            ->where('event_date', $date)
            ->where('is_active', true)
            ->where(function($q) use ($shiftFrom, $shiftTo) {
                // Check if new shift overlaps with existing shifts
                $q->where(function($subQ) use ($shiftFrom, $shiftTo) {
                    // New shift starts during existing shift
                    $subQ->where('shift_from', '<=', $shiftFrom)
                         ->where('shift_to', '>', $shiftFrom);
                })->orWhere(function($subQ) use ($shiftFrom, $shiftTo) {
                    // New shift ends during existing shift
                    $subQ->where('shift_from', '<', $shiftTo)
                         ->where('shift_to', '>=', $shiftTo);
                })->orWhere(function($subQ) use ($shiftFrom, $shiftTo) {
                    // New shift completely contains existing shift
                    $subQ->where('shift_from', '>=', $shiftFrom)
                         ->where('shift_to', '<=', $shiftTo);
                });
            });

        if ($excludeEventId) {
            $query->where('event_id', '!=', $excludeEventId);
        }

        return $query->exists();
    }
}
