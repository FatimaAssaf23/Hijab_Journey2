<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\ScheduledEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleEditorService
{
    /**
     * Update a scheduled event.
     *
     * @param ScheduledEvent $event
     * @param array $data
     * @param int|null $adminId
     * @return ScheduledEvent
     */
    public function updateEvent(ScheduledEvent $event, array $data, ?int $adminId = null): ScheduledEvent
    {
        DB::beginTransaction();
        try {
            $updateData = [];
            
            if (isset($data['release_date'])) {
                $updateData['release_date'] = Carbon::parse($data['release_date'])->toDateString();
            }
            
            if (isset($data['event_type'])) {
                $updateData['event_type'] = $data['event_type'];
            }
            
            if (isset($data['lesson_id'])) {
                $updateData['lesson_id'] = $data['lesson_id'];
            }
            
            if (isset($data['level_id'])) {
                $updateData['level_id'] = $data['level_id'];
            }
            
            if (isset($data['assignment_id'])) {
                $updateData['assignment_id'] = $data['assignment_id'];
            }
            
            if (isset($data['quiz_id'])) {
                $updateData['quiz_id'] = $data['quiz_id'];
            }
            
            if (isset($data['admin_notes'])) {
                $updateData['admin_notes'] = $data['admin_notes'];
            }
            
            // Mark as edited by admin if admin is making changes
            if ($adminId) {
                $updateData['edited_by_admin'] = true;
                $updateData['admin_id'] = $adminId;
            }
            
            $event->update($updateData);
            
            DB::commit();
            
            Log::info("Schedule event updated", [
                'event_id' => $event->event_id,
                'admin_id' => $adminId,
                'changes' => $updateData,
            ]);
            
            return $event->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update schedule event", [
                'event_id' => $event->event_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a new scheduled event.
     *
     * @param Schedule $schedule
     * @param array $data
     * @param int|null $adminId
     * @return ScheduledEvent
     */
    public function createEvent(Schedule $schedule, array $data, ?int $adminId = null): ScheduledEvent
    {
        DB::beginTransaction();
        try {
            $eventData = [
                'schedule_id' => $schedule->schedule_id,
                'event_type' => $data['event_type'],
                'release_date' => Carbon::parse($data['release_date'])->toDateString(),
                'status' => 'pending',
                'lesson_id' => $data['lesson_id'] ?? null,
                'level_id' => $data['level_id'] ?? null,
                'assignment_id' => $data['assignment_id'] ?? null,
                'quiz_id' => $data['quiz_id'] ?? null,
                'edited_by_admin' => $adminId ? true : false,
                'admin_id' => $adminId,
                'admin_notes' => $data['admin_notes'] ?? null,
            ];
            
            $event = ScheduledEvent::create($eventData);
            
            DB::commit();
            
            Log::info("Schedule event created", [
                'event_id' => $event->event_id,
                'schedule_id' => $schedule->schedule_id,
                'admin_id' => $adminId,
            ]);
            
            return $event;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create schedule event", [
                'schedule_id' => $schedule->schedule_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a scheduled event.
     *
     * @param ScheduledEvent $event
     * @param bool $shiftSubsequent Whether to shift subsequent events
     * @return void
     */
    public function deleteEvent(ScheduledEvent $event, bool $shiftSubsequent = false): void
    {
        DB::beginTransaction();
        try {
            $releaseDate = $event->release_date;
            $scheduleId = $event->schedule_id;
            
            $event->delete();
            
            // If shifting, move all subsequent events up by the gap
            if ($shiftSubsequent) {
                $subsequentEvents = ScheduledEvent::where('schedule_id', $scheduleId)
                    ->where('release_date', '>', $releaseDate)
                    ->orderBy('release_date', 'asc')
                    ->get();
                
                // Calculate gap (for now, just remove one week if it was weekly)
                // This is simplified - you might want more sophisticated logic
                foreach ($subsequentEvents as $subsequentEvent) {
                    $newDate = Carbon::parse($subsequentEvent->release_date)->subWeek();
                    $subsequentEvent->update(['release_date' => $newDate->toDateString()]);
                }
            }
            
            DB::commit();
            
            Log::info("Schedule event deleted", [
                'event_id' => $event->event_id,
                'shift_subsequent' => $shiftSubsequent,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete schedule event", [
                'event_id' => $event->event_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Bulk update events (shift dates, change status, etc.).
     *
     * @param Schedule $schedule
     * @param array $eventIds
     * @param array $updates
     * @return int Number of updated events
     */
    public function bulkUpdateEvents(Schedule $schedule, array $eventIds, array $updates): int
    {
        DB::beginTransaction();
        try {
            $query = ScheduledEvent::where('schedule_id', $schedule->schedule_id)
                ->whereIn('event_id', $eventIds);
            
            $updateData = [];
            
            if (isset($updates['shift_days'])) {
                // Shift all dates by X days
                $events = $query->get();
                foreach ($events as $event) {
                    $newDate = Carbon::parse($event->release_date)
                        ->addDays($updates['shift_days'])
                        ->toDateString();
                    $event->update(['release_date' => $newDate]);
                }
                $count = $events->count();
            } else {
                // Regular bulk update
                if (isset($updates['status'])) {
                    $updateData['status'] = $updates['status'];
                }
                
                if (isset($updates['edited_by_admin'])) {
                    $updateData['edited_by_admin'] = $updates['edited_by_admin'];
                }
                
                if (isset($updates['admin_id'])) {
                    $updateData['admin_id'] = $updates['admin_id'];
                }
                
                $count = $query->update($updateData);
            }
            
            DB::commit();
            
            Log::info("Bulk update performed on schedule events", [
                'schedule_id' => $schedule->schedule_id,
                'event_count' => $count,
                'updates' => $updates,
            ]);
            
            return $count;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to bulk update schedule events", [
                'schedule_id' => $schedule->schedule_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Reorder events by updating their release dates.
     *
     * @param Schedule $schedule
     * @param array $eventOrder Array of event_id => new_release_date
     * @return void
     */
    public function reorderEvents(Schedule $schedule, array $eventOrder): void
    {
        DB::beginTransaction();
        try {
            foreach ($eventOrder as $eventId => $newDate) {
                $event = ScheduledEvent::where('schedule_id', $schedule->schedule_id)
                    ->findOrFail($eventId);
                
                $event->update([
                    'release_date' => Carbon::parse($newDate)->toDateString(),
                    'edited_by_admin' => true,
                ]);
            }
            
            DB::commit();
            
            Log::info("Schedule events reordered", [
                'schedule_id' => $schedule->schedule_id,
                'events_reordered' => count($eventOrder),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to reorder schedule events", [
                'schedule_id' => $schedule->schedule_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
