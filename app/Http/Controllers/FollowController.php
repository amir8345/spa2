<?php

namespace App\Http\Controllers;

use App\Models\MainUser;
use Illuminate\Http\Request;
use App\Models\MainPublisher;
use App\Models\MainContributor;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\MainUserResource;
use App\Http\Resources\MainPublisherResource;

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


    public function followers($following_type , $following_id , $page)
    {
        $offset = ($page - 1 ) * 20;

        if ($following_type == 'publisher') {
            $model = MainPublisher::find($following_id);
        }
        if ($following_type == 'user') {
            $model = MainUser::find($following_id);
        }

        $followers = $model->followers()
        ->offset($offset)
        ->limit(20)
        ->get();

        return MainUserResource::collection($followers);

    }

    public function followings($follower_id , $following_type , $page)
    {
        $offset = ($page - 1 ) * 20;

        $user = MainUser::find($follower_id);


        if ($following_type == 'user') {
            
            $followings = $user->followings_user()
            ->offset($offset)
            ->limit(20)
            ->get();

            return MainUserResource::collection($followings);

        }

        if ($following_type == 'publisher') {

            $followings = $user->followings_publisher()
            ->offset($offset)
            ->limit(20)
            ->get();

            return MainPublisherResource::collection($followings);

        }


    }


}
