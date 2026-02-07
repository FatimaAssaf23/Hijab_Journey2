<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceConfirmation extends Model
{
    protected $fillable = ['meeting_enrollment_id', 'confirmation_number', 'prompted_at', 'responded_at', 'is_confirmed'];
    
    protected function casts(): array
    {
        return [
            'prompted_at' => 'datetime',
            'responded_at' => 'datetime',
            'is_confirmed' => 'boolean',
        ];
    }
    
    public function enrollment()
    {
        return $this->belongsTo(MeetingEnrollment::class);
    }
}
