<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_crawled_at',
    ];


    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

}
