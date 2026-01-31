<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $primaryKey = 'genre_id';
    
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    // Relationships
    public function comics()
    {
        return $this->belongsToMany(
            Comic::class,
            'comic_genres',
            'genre_id',
            'comic_id',
            'genre_id',
            'comic_id'
        );
    }

    // Helper methods
    public function totalComics()
    {
        return $this->comics()->count();
    }
}
