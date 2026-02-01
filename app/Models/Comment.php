<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $primaryKey = 'comment_id';
    
    protected $fillable = [
        'user_id',
        'chapter_id',
        'comic_id', // Added
        'parent_comment_id',
        'comment_text',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'chapter_id');
    }

    public function comic() // Added
    {
        return $this->belongsTo(Comic::class, 'comic_id', 'comic_id');
    }

    // Self-referencing for nested comments
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_comment_id', 'comment_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_comment_id', 'comment_id');
    }

    // Helper methods
    public function isReply()
    {
        return $this->parent_comment_id !== null;
    }
}
