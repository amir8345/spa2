<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShelfResource;
use App\Models\Shelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShelfController extends Controller
{
    public function add(Request $request)
    {
        $new_shelf_id = DB::table('shelves')->insertGetId([
            'user_id' => $request->user()->id,
            'name' => $request->name
        ]);
    
        if (! $new_shelf_id) {
            return 'did not insert';
        }

        return 'inserted successfully';

    }
   
    public function delete(Shelf $shelf)
    {

        if ($shelf->delete() != 1) {
            return 'something is wrong';
        }

        return 'deleted successfully';

    }
    
    public function update(Shelf $shelf)
    {

        if ($shelf->update(['name' => request()->name]) != 1) {
            return 'something is wrong';
        }

        return 'updated successfully';

    }
    
    public function show(Shelf $shelf)
    {
        return new ShelfResource($shelf);
    }

    public function library()
    {
        $user = request()->user();
        return ShelfResource::collection($user->shelves);

    }

    public function shelves()
    {
        $shelves = DB::table('shelves')
        ->where('user_id' , request()->user()->id)
        ->pluck('id' , 'name');

        return $shelves->toJson();
    }

}
