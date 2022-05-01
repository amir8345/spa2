<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contributor extends Model
{
    use HasFactory;


    public function posts()
    {
        return $this->hasMany(Post::class);
    }
  
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }


}
