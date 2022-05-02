<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Score;
use App\Models\Shelf;
use App\Models\Comment;
use App\Models\Publisher;
use App\Models\Contributor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }
    

    public function shelves()
    {
        return $this->belongsToMany(Shelf::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    

    public function writers()
    {
        return $this->belongsToMany(Contributor::class)->wherePivot('action' , 'writer');
    }

    public function translators()
    {
        return $this->belongsToMany(Contributor::class)->wherePivot('action' , 'translator');
    }

    public function editors()
    {
        return $this->belongsToMany(Contributor::class)->wherePivot('action' , 'editor');
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    

}
