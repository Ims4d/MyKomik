<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    use HasFactory;

    protected $primaryKey = 'comic_id';
    
    protected $fillable = [
        'title',
        'synopsis',
        'author',
        'cover_image_url',
        'status',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'comic_id', 'comic_id')
                    ->orderBy('chapter_number', 'asc');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'comic_id', 'comic_id');
    }

    public function genres()
    {
        return $this->belongsToMany(
            Genre::class,
            'comic_genres',
            'comic_id',
            'genre_id',
            'comic_id',
            'genre_id'
        );
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'comic_id', 'comic_id');
    }

    // Helper methods
    public function averageRating()
    {
        return $this->ratings()->avg('rating_value') ?? 0;
    }

    public function totalRatings()
    {
        return $this->ratings()->count();
    }

    public function totalChapters()
    {
        return $this->chapters()->count();
    }
}
