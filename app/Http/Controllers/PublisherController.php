<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Models\MainPublisher;
use App\Models\BookContributor;
use App\Models\MainContributor;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PublisherResource;
use App\Http\Resources\MainPublisherResource;
use App\Http\Resources\MainContributorResource;

class PublisherController extends Controller
{

    public function get_publishers($order , $page)
    {
        $offset = ($page - 1) * 20;

        $publishers = MainPublisher::orderByDesc($order)
        ->offset($offset)
        ->limit(20)
        ->get();

        return MainPublisherResource::collection($publishers);

    }

    public function one(MainPublisher $publisher)
    {
        return new MainPublisherResource($publisher);
    }

    public function contributors(MainPublisher $publisher , $contributor_type , $order , $page)
    {

        $offset = ($page - 1) * 20;

        $book_ids = $publisher->books->pluck('id');

        $contributor_ids = DB::table('book_contributor')
        ->whereIn('book_id' , $book_ids)
        ->where('action' , $contributor_type)
        ->pluck('contributor_id');
        
        $contributors = MainContributor::whereIn('id' , $contributor_ids->toArray())
        ->orderByDesc($order)
        ->offset($offset)
        ->limit(20)
        ->get();

        return MainContributorResource::collection($contributors);

    }



}
