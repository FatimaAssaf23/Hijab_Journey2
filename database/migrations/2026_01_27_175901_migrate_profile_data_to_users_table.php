<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migrate profile data from admin_profiles and teacher_profiles to users table
     */
    public function up(): void
    {
        // Migrate admin profiles data to users table
        if (Schema::hasTable('admin_profiles')) {
            $adminProfiles = DB::table('admin_profiles')->get();
            foreach ($adminProfiles as $adminProfile) {
                $user = DB::table('users')->where('user_id', $adminProfile->user_id)->first();
                if ($user) {
                    // Only update if user doesn't already have this data
                    $updateData = [];
                    if ($adminProfile->profile_photo_path && !$user->profile_photo_path) {
                        $updateData['profile_photo_path'] = $adminProfile->profile_photo_path;
                    }
                    if ($adminProfile->bio && !$user->bio) {
                        $updateData['bio'] = $adminProfile->bio;
                    }
                    if (!empty($updateData)) {
                        DB::table('users')
                            ->where('user_id', $adminProfile->user_id)
                            ->update($updateData);
                    }
                }
            }
        }

        // Migrate teacher profiles data to users table
        if (Schema::hasTable('teacher_profiles')) {
            $teacherProfiles = DB::table('teacher_profiles')->get();
            foreach ($teacherProfiles as $teacherProfile) {
                $user = DB::table('users')->where('user_id', $teacherProfile->user_id)->first();
                if ($user) {
                    // Only update if user doesn't already have this data
                    $updateData = [];
                    if ($teacherProfile->profile_photo_path && !$user->profile_photo_path) {
                        $updateData['profile_photo_path'] = $teacherProfile->profile_photo_path;
                    }
                    if ($teacherProfile->bio && !$user->bio) {
                        $updateData['bio'] = $teacherProfile->bio;
                    }
                    if (!empty($updateData)) {
                        DB::table('users')
                            ->where('user_id', $teacherProfile->user_id)
                            ->update($updateData);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     * Note: This migration cannot be fully reversed as we don't know which data came from which table
     */
    public function down(): void
    {
        // Cannot fully reverse this migration as data is now merged
        // The profile tables will be dropped in a later migration
    }
};
