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
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique();
            $table->text('setting_value')->nullable();
            $table->string('setting_type')->default('string'); // string, boolean, integer, json
            $table->text('description')->nullable();
            $table->string('category')->default('general'); // general, user_management, content, notifications, security, system
            $table->timestamps();
        });

        // Insert default settings
        DB::table('admin_settings')->insert([
            // User Management Settings
            ['setting_key' => 'auto_approve_teachers', 'setting_value' => '0', 'setting_type' => 'boolean', 'description' => 'Automatically approve teacher requests without admin review', 'category' => 'user_management', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'require_email_verification', 'setting_value' => '0', 'setting_type' => 'boolean', 'description' => 'Require email verification for new user registrations', 'category' => 'user_management', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'allow_guest_teacher_applications', 'setting_value' => '1', 'setting_type' => 'boolean', 'description' => 'Allow guest users to apply as teachers', 'category' => 'user_management', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'max_students_per_class', 'setting_value' => '30', 'setting_type' => 'integer', 'description' => 'Maximum number of students allowed per class', 'category' => 'user_management', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'max_classes_per_teacher', 'setting_value' => '5', 'setting_type' => 'integer', 'description' => 'Maximum number of classes a teacher can be assigned to', 'category' => 'user_management', 'created_at' => now(), 'updated_at' => now()],
            
            // Content Moderation Settings
            ['setting_key' => 'require_approval_for_lessons', 'setting_value' => '0', 'setting_type' => 'boolean', 'description' => 'Require admin approval before lessons are published', 'category' => 'content', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'require_approval_for_assignments', 'setting_value' => '0', 'setting_type' => 'boolean', 'description' => 'Require admin approval before assignments are published', 'category' => 'content', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'require_approval_for_quizzes', 'setting_value' => '0', 'setting_type' => 'boolean', 'description' => 'Require admin approval before quizzes are published', 'category' => 'content', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'require_approval_for_games', 'setting_value' => '0', 'setting_type' => 'boolean', 'description' => 'Require admin approval before games are published', 'category' => 'content', 'created_at' => now(), 'updated_at' => now()],
            
            // Notification Settings
            ['setting_key' => 'email_notifications_enabled', 'setting_value' => '1', 'setting_type' => 'boolean', 'description' => 'Enable email notifications system-wide', 'category' => 'notifications', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'notify_admin_on_teacher_requests', 'setting_value' => '1', 'setting_type' => 'boolean', 'description' => 'Send email notification to admin when new teacher requests are submitted', 'category' => 'notifications', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'notify_admin_on_emergency_requests', 'setting_value' => '1', 'setting_type' => 'boolean', 'description' => 'Send email notification to admin when emergency substitution requests are made', 'category' => 'notifications', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'notify_admin_on_new_registrations', 'setting_value' => '0', 'setting_type' => 'boolean', 'description' => 'Send email notification to admin when new users register', 'category' => 'notifications', 'created_at' => now(), 'updated_at' => now()],
            
            // System Settings
            ['setting_key' => 'site_maintenance_mode', 'setting_value' => '0', 'setting_type' => 'boolean', 'description' => 'Enable maintenance mode (only admins can access the site)', 'category' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'site_name', 'setting_value' => 'Hijab Journey', 'setting_type' => 'string', 'description' => 'Website name displayed throughout the platform', 'category' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'max_file_upload_size_mb', 'setting_value' => '10', 'setting_type' => 'integer', 'description' => 'Maximum file upload size in megabytes', 'category' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'allowed_file_types', 'setting_value' => 'pdf,doc,docx,jpg,jpeg,png,gif,mp4,mp3', 'setting_type' => 'string', 'description' => 'Comma-separated list of allowed file extensions', 'category' => 'system', 'created_at' => now(), 'updated_at' => now()],
            
            // Security Settings
            ['setting_key' => 'session_timeout_minutes', 'setting_value' => '120', 'setting_type' => 'integer', 'description' => 'Session timeout in minutes (user will be logged out after inactivity)', 'category' => 'security', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'password_min_length', 'setting_value' => '8', 'setting_type' => 'integer', 'description' => 'Minimum password length required', 'category' => 'security', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'max_login_attempts', 'setting_value' => '5', 'setting_type' => 'integer', 'description' => 'Maximum login attempts before account is temporarily locked', 'category' => 'security', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'lockout_duration_minutes', 'setting_value' => '15', 'setting_type' => 'integer', 'description' => 'Account lockout duration in minutes after max login attempts', 'category' => 'security', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_settings');
    }
};
