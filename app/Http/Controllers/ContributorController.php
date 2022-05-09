<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ContributorResource;

class ContributorController extends Controller
{

    public function all($type , $order ,$page)
    {
        $requested_type_ids = DB::table('book_contributor')
        ->where('action' , $type)
        ->pluck('contributor_id');

        $contributors = Contributor::
        whereIn('id' , $requested_type_ids->toArray())
        // ->orderByRaw($requested_order)
        ->offset( ( $page - 1 ) * 20)
        ->limit(20)
        ->get();

        return ContributorResource::collection($contributors);
    }


    public function one(Contributor $contributor)
    {
        return new ContributorResource($contributor);
    }
}
