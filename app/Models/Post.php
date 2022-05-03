<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function writer()
    {
        return $this->belongsTo(User::class);
    }

    public function receiver()
    {
        return $this->morphTo();
    }


}
