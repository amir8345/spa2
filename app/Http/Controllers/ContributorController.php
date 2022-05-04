<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContributorResource;
use App\Models\Contributor;
use Illuminate\Http\Request;

class ContributorController extends Controller
{
    public function one_contributor(Request $request)
    {
        $contributor = Contributor::find($request->id);
        return new ContributorResource($contributor);
    }
}
