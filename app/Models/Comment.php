<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $primaryKey = 'comment_id';

    public $timestamps = false; // Since we have custom created_at and updated_at

    protected $fillable = [
        'lesson_id',
        'user_id',
        'parent_comment_id',
        'comment_text',
        'is_private',
        'is_deleted',
    ];

    protected function casts(): array
    {
        return [
            'is_private' => 'boolean',
            'is_deleted' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the lesson that owns the comment.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }

    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the parent comment.
     */
    public function parentComment()
    {
        return $this->belongsTo(Comment::class, 'parent_comment_id', 'comment_id');
    }

    /**
     * Get the replies to this comment.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_comment_id', 'comment_id');
    }
}
