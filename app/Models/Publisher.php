<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\MainBook;
use App\Models\PublisherUser;
use App\Models\BookContributor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publisher extends Model
{
    use HasFactory;

    public function books()
    {
        return $this->hasMany(MainBook::class);
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
        return $this->hasOne(PublisherUser::class);
    }
   
    public function contributors()
    {
        return $this->hasManyThrough(BookContributor::class , Book::class);
    }


}
