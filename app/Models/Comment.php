<?php

namespace App\Models;

use App\Models\Comment AS Comment2;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    public function comments()
    {
        return $this->hasMany(Comment2::class);
    }

    public function writer()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
  
    public function receiver()
    {
        return $this->morphTo();
    }

    


}
