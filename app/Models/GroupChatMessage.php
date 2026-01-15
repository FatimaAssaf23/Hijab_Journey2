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
        'reply_to_message_id',
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

    /**
     * Get the message this is a reply to.
     */
    public function replyTo()
    {
        return $this->belongsTo(GroupChatMessage::class, 'reply_to_message_id', 'message_id');
    }

    /**
     * Get all replies to this message.
     */
    public function replies()
    {
        return $this->hasMany(GroupChatMessage::class, 'reply_to_message_id', 'message_id');
    }

    /**
     * Get all reactions to this message.
     */
    public function reactions()
    {
        return $this->hasMany(ChatMessageReaction::class, 'message_id', 'message_id');
    }
}
