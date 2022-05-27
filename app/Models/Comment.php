<?php

namespace App\Models;

use App\Models\Like;
use App\Models\Comment AS Comment2;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['body'];

    public function comments()
    {
        return $this->morphMany(Comment2::class , 'commented');
    }

    public function writer()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
  
    public function parent()
    {
        return $this->morphTo('commented');
    }

    public function likes()
    {
        return $this->morphMany(Like::class , 'liked');
    }



}
