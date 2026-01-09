<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessageReaction extends Model
{
    use HasFactory;

    protected $table = 'group_chat_reactions';

    protected $primaryKey = 'reaction_id';

    protected $fillable = [
        'message_id',
        'user_id',
        'emoji',
    ];

    /**
     * Get the message this reaction belongs to.
     */
    public function message()
    {
        return $this->belongsTo(GroupChatMessage::class, 'message_id', 'message_id');
    }

    /**
     * Get the user who made this reaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
