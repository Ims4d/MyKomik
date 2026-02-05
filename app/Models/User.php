<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'username',
        'password',
        'role',
        'display_name',
        'avatar',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'user_id');
    }

    public function readingProgress()
    {
        return $this->hasMany(UserReadingProgress::class, 'user_id', 'user_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Get user's avatar URL
     * Returns custom avatar if exists, otherwise generates initials avatar
     */
    public function getAvatarUrl()
    {
        return \App\Helpers\AvatarHelper::getAvatarUrl($this);
    }

    /**
     * Get user's initials
     */
    public function getInitials()
    {
        return \App\Helpers\AvatarHelper::getInitials($this);
    }
}