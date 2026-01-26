<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'country',
        'role',
        'profile_image_url',
        'profile_photo_path',
        'bio',
        'phone_number',
        'date_joined',
        'is_admin',
    ];
    /**
     * Get the full URL for the profile photo.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        return asset('images/default-profile.png');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
                'email',
                'password',
                'first_name',
                'last_name',
                'role',
                'profile_image_url',
                'bio',
                'phone_number',
                'date_joined',
                'is_admin',
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'date_joined' => 'datetime',
        ];
    }

    /**
     * Get the student profile associated with the user.
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id', 'user_id');
    }

    /**
     * Get the teacher profile associated with the user.
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id', 'user_id');
    }

    /**
     * Get the teacher profile details associated with the user.
     */
    public function teacherProfile()
    {
        return $this->hasOne(TeacherProfile::class, 'user_id', 'user_id');
    }

    /**
     * Get the teacher request associated with the user (as guest).
     */
    public function teacherRequest()
    {
        return $this->hasOne(TeacherRequest::class, 'user_id', 'user_id');
    }

    /**
     * Get the teacher requests processed by this admin user.
     */
    public function processedTeacherRequests()
    {
        return $this->hasMany(TeacherRequest::class, 'approved_by_admin_id', 'user_id');
    }

    /**
     * Get the classes taught by this teacher.
     */
    public function taughtClasses()
    {
        return $this->hasMany(StudentClass::class, 'teacher_id', 'user_id');
    }

    /**
     * Get the grades given by this teacher.
     */
    public function givenGrades()
    {
        return $this->hasMany(Grade::class, 'teacher_id', 'user_id');
    }

    /**
     * Get the private messages sent by this user.
     */
    public function sentMessages()
    {
        return $this->hasMany(PrivateMessage::class, 'sender_id', 'user_id');
    }

    /**
     * Get the private messages received by this user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(PrivateMessage::class, 'receiver_id', 'user_id');
    }

    /**
     * Get the group chat messages sent by this user.
     */
    public function sentGroupChatMessages()
    {
        return $this->hasMany(GroupChatMessage::class, 'sender_id', 'user_id');
    }

    /**
     * Get the meetings scheduled by this teacher.
     */
    public function scheduledMeetings()
    {
        return $this->hasMany(Meeting::class, 'teacher_id', 'user_id');
    }

    /**
     * Get the comments posted by this user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'user_id');
    }

    /**
     * Get the teacher substitutions where this user is the original teacher.
     */
    public function originalTeacherSubstitutions()
    {
        return $this->hasMany(TeacherSubstitution::class, 'original_teacher_id', 'user_id');
    }

    /**
     * Get the teacher substitutions where this user is the substitute teacher.
     */
    public function substituteTeacherSubstitutions()
    {
        return $this->hasMany(TeacherSubstitution::class, 'substitute_teacher_id', 'user_id');
    }

    /**
     * Get the teacher substitutions requested by this admin.
     */
    public function requestedTeacherSubstitutions()
    {
        return $this->hasMany(TeacherSubstitution::class, 'requested_by_admin_id', 'user_id');
    }

    /**
     * Get the class lesson visibilities updated by this teacher.
     */
    public function classLessonVisibilities()
    {
        return $this->hasMany(ClassLessonVisibility::class, 'teacher_id', 'user_id');
    }


}