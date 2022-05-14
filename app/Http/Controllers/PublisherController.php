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

    public function contributors(Publisher $publisher , $contributor_type)
    {
        $contributors_ids = $publisher->contributors->where('action' , $contributor_type)->pluck('id');
        // return MainContributor::whereIn('id' , $contributors_ids)->get();
    }


}
