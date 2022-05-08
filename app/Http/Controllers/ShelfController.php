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

        if ($shelf->user_id != request()->user()->id) {
            return 'you are not authorized';
        }

        if ($shelf->delete() != 1) {
            return 'something is wrong';
        }

        return 'deleted successfully';

    }
    
    public function update(Shelf $shelf)
    {

        if ($shelf->user_id != request()->user()->id) {
            return 'you are not authorized';
        }

        if ($shelf->update(['name' => request()->name]) != 1) {
            return 'something is wrong';
        }

        return 'updated successfully';

    }
    

    public function show(Shelf $shelf)
    {
        return new ShelfResource($shelf);
    }



}
