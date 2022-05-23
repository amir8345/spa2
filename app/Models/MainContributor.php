<?php

namespace App\Models;

use App\Models\SocialMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function books(string $type)
    {
        return $this->belongsToMany(MainBook::class , 'book_contributor' , 'contributor_id' , 'book_id')->wherePivot('action' , $type);
    }

    public function user()
    {
        return $this->hasOne(ContributorUser::class , 'contributor_id');
    }

    public function social_medias()
    {
        return $this->morphMany(SocialMedia::class , 'owner');
    }

}
