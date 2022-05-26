<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MainUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use App\Http\Resources\MainUserResource;
use App\Http\Resources\MainPublisherResource;

class UserController extends Controller
{
 
    public function get_users($order , $page)
    {
        $offset = ($page - 1) * 20;
        
        $contributor_user_ids = DB::table('contributor_user')->pluck('user_id');
        $publisher_user_ids = DB::table('publisher_user')->pluck('user_id');

        $users = MainUser::
        whereNotIn('id' , $contributor_user_ids->toArray())
        ->whereNotIn('id' , $publisher_user_ids->toArray())
        ->orderByDesc($order)
        ->offset($offset)
        ->limit(20)
        ->get();
        
        return MainUserResource::collection($users);
    }
  
    
    public function one(MainUser $user)
    {
        return new MainUserResource($user);   
    }

}
