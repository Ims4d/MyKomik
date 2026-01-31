<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $primaryKey = 'chapter_id';
    
    protected $fillable = [
        'comic_id',
        'chapter_number',
        'title',
        'release_date',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public $timestamps = false;

    // Relationships
    public function comic()
    {
        return $this->belongsTo(Comic::class, 'comic_id', 'comic_id');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'chapter_id', 'chapter_id')
                    ->orderBy('page_number', 'asc');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'chapter_id', 'chapter_id');
    }

    public function readingProgress()
    {
        return $this->hasMany(UserReadingProgress::class, 'chapter_id', 'chapter_id');
    }

    // Helper methods
    public function totalPages()
    {
        return $this->pages()->count();
    }

    public function totalComments()
    {
        return $this->comments()->count();
    }
}
