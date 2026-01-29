<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'request_id';

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'age',
        'approved_by_admin_id',
        'language',
        'specialization',
        'experience_years',
        'university_major',
        'courses_done',
        'certification_file',
        'status',
        'request_date',
        'processed_date',
        'rejection_reason',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'request_date' => 'datetime',
            'processed_date' => 'datetime',
            'experience_years' => 'integer',
            'age' => 'integer',
        ];
    }

    /**
     * Get the user that owns the teacher request.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the admin user who processed the request.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'approved_by_admin_id', 'user_id');
    }
}
