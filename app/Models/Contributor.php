<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Post;
use App\Models\Comment;
use App\Models\MainBook;
use App\Models\ContributorUser;
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
        return $this->morphMany(Comment::class, 'commented');
    }

    public function books()
    {
        return $this->belongsToMany(MainBook::class , 'book_contributor' , 'contributor_id' , 'book_id');
    }

    public function user()
    {
        return $this->hasOne(ContributorUser::class);
    }


}
