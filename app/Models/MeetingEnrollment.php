<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingEnrollment extends Model
{
    protected $fillable = ['meeting_id', 'student_id', 'attendance_status', 'joined_at', 'left_at'];
    
    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
            'left_at' => 'datetime',
        ];
    }
    
    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'meeting_id', 'meeting_id');
    }
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }
    
    public function confirmations()
    {
        return $this->hasMany(AttendanceConfirmation::class);
    }
    
    public function calculateFinalStatus()
    {
        $missedCount = $this->confirmations()
            ->where(function($q) {
                $q->where('is_confirmed', false)->orWhereNull('responded_at');
            })->count();
        
        return $missedCount > 2 ? 'absent' : 'present';
    }
}
