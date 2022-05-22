<?php

namespace App\Models;

use App\Models\MainBook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class MainPublisher extends Model
{
    use HasFactory;

    protected $table = 'main_publisher';

    public function books()
    {
        return $this->hasMany(MainBook::class , 'publisher_id');
    }
    
    public function posts()
    {
        return $this->morphMany(Post::class, 'posted');
    }
   
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commented');
    }

    public function followers()
    {
        return $this->morphToMany(User::class , 'following' , 'follows' , 'following_id' , 'follower_id');
    }

    public function user()
    {
        return $this->hasOne(PublisherUser::class , 'publisher_id');
    }

    public function social_medias()
    {
        return $this->morphMany('SocialMedia' , 'owner');
    }
   

}
