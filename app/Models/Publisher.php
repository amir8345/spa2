<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publisher extends Model
{
    use HasFactory;

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function posts_by()
    {
        return $this->morphMany(Post::class , 'writer');
    }
  
    public function comments_by()
    {
        return $this->morphMany(Comment::class , 'wirter');
    }
    
    public function posts_on()
    {
        return $this->morphMany(Post::class, 'receiver');
    }
   
    public function comments_on()
    {
        return $this->morphMany(Comment::class, 'receiver');
    }

    public function followers()
    {
        return $this->morphToMany(User::class , 'following');
    }


}
