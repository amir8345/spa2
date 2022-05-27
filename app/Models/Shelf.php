<?php

namespace App\Models;

use App\Models\MainBook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shelf extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany(MainBook::class , 'book_shelf' , 'shelf_id' , 'book_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
