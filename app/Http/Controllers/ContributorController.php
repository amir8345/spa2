<?php

namespace App\Http\Controllers;

use App\Models\Contributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ContributorResource;

class ContributorController extends Controller
{

    public function all_contributors(Request $request)
    {

        $requested_type_ids = DB::table('book_contributor')
        ->where('action' , $request->type)
        ->pluck('contributor_id');

        $contributors = Contributor::
        whereIn('id' , $requested_type_ids->toArray())
        // ->orderByRaw($requested_order)
        ->offset( ( $request->page - 1 ) * 20)
        ->limit(20)
        ->get();

        return ContributorResource::collection($contributors);
    }


    public function one_contributor(Contributor $contributor)
    {
        return new ContributorResource($contributor);
    }
}
