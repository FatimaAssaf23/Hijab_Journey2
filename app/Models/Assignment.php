<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $primaryKey = 'assignment_id';

    protected $fillable = [
        'level_id',
        'class_id',
        'teacher_id',
        'checked_by_admin_id',
        'title',
        'description',
        'pdf_url',
        'posted_date',
        'due_date',
        'max_score',
        'approval_status',
    ];

    protected function casts(): array
    {
        return [
            'posted_date' => 'datetime',
            'due_date' => 'datetime',
            'max_score' => 'integer',
        ];
    }

    /**
     * Get the level that owns the assignment.
     */
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'level_id');
    }

    /**
     * Get the student class that owns the assignment.
     */
    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    /**
     * Get the teacher that assigned the assignment.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }

    /**
     * Get the admin who checked the assignment.
     */
    public function checkedByAdmin()
    {
        return $this->belongsTo(User::class, 'checked_by_admin_id', 'user_id');
    }

    /**
     * Get the submissions for the assignment.
     */
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'assignment_id', 'assignment_id');
    }
}