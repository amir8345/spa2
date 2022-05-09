<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublisherResource;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{

    public function all()
    {
        $publishers = Publisher::all();
        return PublisherResource::collection($publishers);
    }


    public function one_publisher(Request $request)
    {
        $publisher = Publisher::find($request->id);
        return new PublisherResource($publisher);
    }
}
