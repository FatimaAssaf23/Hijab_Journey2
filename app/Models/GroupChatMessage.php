<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupChatMessage extends Model
{
    use HasFactory;

    protected $primaryKey = 'message_id';

    protected $fillable = [
        'class_id',
        'sender_id',
        'content',
        'sent_at',
        'is_deleted',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'is_deleted' => 'boolean',
        ];
    }

    /**
     * Get the student class that owns the message.
     */
    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id', 'class_id');
    }

    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }
}
