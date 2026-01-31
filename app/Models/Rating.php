<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comic_id',
        'rating_value',
    ];

    public $incrementing = false;
    protected $primaryKey = ['user_id', 'comic_id'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function comic()
    {
        return $this->belongsTo(Comic::class, 'comic_id', 'comic_id');
    }
}
