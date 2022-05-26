<?php

namespace App\Models;

use App\Models\MainBook;
use App\Models\SocialMedia;
use App\Models\PublisherContributor;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\PublisherController;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function followings_user()
    {
        return $this->morphedByMany(User::class , 'following' , 'follows' , 'follower_id' , 'following_id');
    }

    public function followings_publisher()
    {
        return $this->morphedByMany(Publisher::class , 'following' , 'follows' , 'follower_id' , 'following_id');
    }


    public function user()
    {
        return $this->hasOne(PublisherUser::class , 'publisher_id');
    }

    public function social_medias()
    {
        return $this->morphMany(SocialMedia::class , 'owner');
    }

    public function contributors()
    {
        return $this->hasMany(PublisherContributor::class , 'publisher_id');
    }
   

}
