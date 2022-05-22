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


class MainBook extends Model
{
    use HasFactory;

    protected $table = 'main_book';

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function shelves()
    {
        return $this->belongsToMany(Shelf::class , 'book_shelf' , 'book_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class ,'book_tag' , 'book_id');
    }
    
    public function posts()
    {
        return $this->morphMany(Post::class , 'posted');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class , 'commented');
    }

    public function contributors(string $type)
    {
        return $this
        ->belongsToMany(MainContributor::class , 'book_contributor' , 'book_id' , 'contributor_id')
        ->wherePivot('action' , $type);
    }
    
    public function scores()
    {
        return $this->hasMany(Score::class , 'book_id');
    }

   
}
