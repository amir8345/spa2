<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shelf;
use App\Models\MainUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ShelfResource;

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
   
    public function delete(Request $request)
    {

        $shelf = Shelf::find($request->shelf_id);
        
        if ($shelf->delete() != 1) {
            return 'could not delete shelf';
        }
        
        return 'deleted successfully';
        
    }
    
    public function update(Request $request)
    {
        $shelf = Shelf::find($request->shelf_id);
        
        if ($shelf->update(['name' => $request->name]) != 1) {
            return 'could not update shelf';
        }

        return 'updated successfully';

    }
    
    public function show(Shelf $shelf)
    {
        return new ShelfResource($shelf);
    }

    public function library(User $user)
    {   
        $main_user = MainUser::find($user->id);

        return [
            'library' => ShelfResource::collection($main_user->shelves),
            'last_update' => $main_user->library_last_update->last_update
        ];
    }
      

    public function shelves()
    {
        return ShelfResource::collection(request()->user()->shelves);
    }

}
