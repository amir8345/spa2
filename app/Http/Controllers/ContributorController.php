<?php

namespace App\Http\Controllers;

use App\Models\MainContributor;
use App\Http\Resources\MainContributorResource;
use Illuminate\Support\Facades\DB;

class ContributorController extends Controller
{

    public function get_contributors($type , $order ,$page)
    {

        $offset = ($page - 1) * 20;

        $asc_desc = 'desc';

        if ($order == 'name') {
            $asc_desc = 'asc';
        }

        $contributor_ids = DB::table('book_contributor')
        ->where('action' , $type)
        ->pluck('contributor_id');

        $contributors = MainContributor::whereIn('id' , $contributor_ids->toArray())
        ->orderBy($order , $asc_desc)
        ->offset($offset)
        ->limit(20)
        ->get();

        return MainContributorResource::collection($contributors);

    }


    public function one(MainContributor $contributor)
    {
        return new MainContributorResource($contributor);
    }
}
