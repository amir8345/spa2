<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MainUserResource;
use App\Models\MainUser;

class LikeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toggle_like(Request $request)
    {
                
        $user_data = [
            ['user_id' , '=' , $request->user()->id ],
            ['liked_type' , '=' , $request->type ],
            ['liked_id' , '=' , $request->id ],
        ];
        
        
        if (DB::table('likes')->where($user_data)->delete() == 1) {
            return 'deleted successfully';
        }
        
        DB::table('likes')->insert($user_data);
        return 'liked successfully';
    }


    public function likers($type , $id)
    {

        $likers_ids = DB::table('likes')
        ->where('liked_type' , $type)
        ->where('liked_id' , $id)
        ->pluck('user_id');

        $likers = MainUser::whereIn('id' , $likers_ids->toArray())->get();

        return MainUserResource::collection($likers);

    }



}
