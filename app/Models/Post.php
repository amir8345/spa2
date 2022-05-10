<?php

namespace App\Models;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commented');
    }

    public function writer()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function receiver()
    {
        return $this->morphTo();
    }

    public function likes()
    {
        return $this->morphMany(Like::class , 'liked');
    }

}
