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

        $contributors = MainContributor::orderByDesc($order)
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
