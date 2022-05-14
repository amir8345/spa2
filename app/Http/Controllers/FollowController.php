<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    public function add(Request $request)
    {
        $new_follow = DB::table('follows')->insertGetId([
            'follower_id' => $request->user()->id,
            'following_type' => $request->type,
            'following_id' => $request->id,
            'status' => 'a'
        ]);

        if (! $new_follow) {
            return 'could not enter follow request';
        }

        return 'follow request inserted successfully';

    }
    
    public function delete(Request $request)
    {
        $follow = DB::table('follows')
        ->where('following_type' , $request->type)
        ->where('following_id' , $request->id)
        ->first();

        if ($follow->delete() != 1) {
            return 'something is wronge';
        }

        return 'deleted successfully';

    }


    public function update(Request $request)
    {
        $updated_follow = DB::table('follows')
        ->where(
            ['follower_id' , '=' , $request->id],
            ['following_type' , '=' , 'user'],
            ['following_id' , '=' , $request->user()->id],
        )
        ->update(['status' => $request->status]);

        if ($updated_follow != 1) {
            return 'could not update follow status';
        }

        return 'updated successfully';

    }

}
