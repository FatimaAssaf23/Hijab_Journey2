<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceCheckResponse extends Model
{
    use HasFactory;

    protected $primaryKey = 'response_id';

    protected $fillable = [
        'attendance_id',
        'check_number',
        'response',
        'checked_at',
    ];

    protected function casts(): array
    {
        return [
            'check_number' => 'integer',
            'checked_at' => 'datetime',
        ];
    }

    /**
     * Get the attendance that this response belongs to.
     */
    public function attendance()
    {
        return $this->belongsTo(MeetingAttendance::class, 'attendance_id', 'attendance_id');
    }
}
