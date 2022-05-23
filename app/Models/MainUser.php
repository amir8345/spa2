<?php

namespace App\Models;

use App\Models\SocialMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MainUser extends Model
{
    use HasFactory;

    protected $table = 'main_user';
    
    public function posts_by()
    {
        return $this->hasMany(Post::class , 'user_id');
    }
  
    public function comments_by()
    {
        return $this->hasMany(Comment::class , 'user_id');
    }

    public function posts()
    {
        return $this->morphMany(Post::class , 'posted');
    }
    
    public function comments()
    {
        return $this->morphMany(Comment::class , 'commented');
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
        return $this->hasMany(Score::class , 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class , 'user_id');
    }

    public function shelves()
    {
        return $this->hasMany(Shelf::class , 'user_id');
    }

    public function publisher()
    {
        return $this->hasOne(PublisherUser::class);
    }
   
    public function contributor()
    {
        return $this->hasOne(ContributorUser::class);
    }

    public function social_medias()
    {
        return $this->morphMany(SocialMedia::class , 'owner');
    }


}

