<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainContributor extends Model
{
    use HasFactory;

    protected $table = 'main_contributor';

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
        return $this->hasOne(ContributorUser::class , 'contributor_id');
    }

}
