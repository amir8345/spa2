<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Follow;
use App\Models\PublisherUser;
use App\Models\ContributorUser;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        return $this->morphToMany(User::class , 'following' , 'follows' , 'following_id' , 'follower_id');
    }

    public function followings_user()
    {
        return $this->morphedByMany(User::class , 'following' , 'follows' , 'follower_id' , 'following_id');
    }

    public function followings_publisher()
    {
        return $this->morphedByMany(Publisher::class , 'following' , 'follows' , 'follower_id' , 'following_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function shelves()
    {
        return $this->hasMany(Shelf::class);
    }

    public function publisher()
    {
        return $this->hasOne(PublisherUser::class);
    }
   
    public function contributor()
    {
        return $this->hasOne(ContributorUser::class);
    }



}
