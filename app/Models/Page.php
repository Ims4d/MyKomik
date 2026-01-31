<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $primaryKey = 'page_id';
    
    protected $fillable = [
        'comic_id',
        'chapter_id',
        'page_number',
        'image_url',
    ];

    public $timestamps = false;

    // Relationships
    public function comic()
    {
        return $this->belongsTo(Comic::class, 'comic_id', 'comic_id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'chapter_id');
    }
}
