<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts_by()
    {
        return $this->morphMany(Post::class , 'writer');
    }
  
    public function comments_by()
    {
        return $this->morphMany(Comment::class , 'wirter');
    }
    
    public function followers()
    {
        return $this->morphToMany(User::class , 'follower');
    }

    public function followings_users()
    {
        return $this->morphedByMany(User::class , 'followings');
    }

    public function followings_publishers()
    {
        return $this->morphedByMany(Publisher::class , 'followings');
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function shelves()
    {
        return $this->belongsToMany(Shelf::class);
    }
}
