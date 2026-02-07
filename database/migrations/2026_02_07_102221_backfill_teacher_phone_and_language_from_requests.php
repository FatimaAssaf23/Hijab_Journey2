<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill phone and language for existing teachers from their approved teacher requests
        $teachers = DB::table('users')
            ->where('role', 'teacher')
            ->get();

        foreach ($teachers as $teacher) {
            // Find approved teacher request for this user
            $teacherRequest = DB::table('teacher_requests')
                ->where('user_id', $teacher->user_id)
                ->where('status', 'approved')
                ->orderBy('processed_date', 'desc')
                ->first();

            if ($teacherRequest) {
                $updates = [];

                // Update phone_number if it's missing in user but exists in request
                if (empty($teacher->phone_number) && !empty($teacherRequest->phone)) {
                    $updates['phone_number'] = $teacherRequest->phone;
                }

                // Update language if it's missing in user but exists in request
                if (empty($teacher->language) && !empty($teacherRequest->language)) {
                    $updates['language'] = $teacherRequest->language;
                }

                // Apply updates if any
                if (!empty($updates)) {
                    DB::table('users')
                        ->where('user_id', $teacher->user_id)
                        ->update($updates);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration only backfills data, so there's nothing to reverse
        // The data remains in the users table
    }
};
