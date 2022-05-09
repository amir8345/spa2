<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublisherResource;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{

    public function all($order , $page)
    {
        $publishers = Publisher::all();
        return PublisherResource::collection($publishers);
    }


    public function one(Publisher $publisher)
    {
        return new PublisherResource($publisher);
    }
}
