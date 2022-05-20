<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MainUser;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\MainUserResource;
use App\Http\Resources\MainPublisherResource;

class UserController extends Controller
{
 
    public function get_users($order , $page)
    {
        $offset = ($page - 1) * 20;
        
        $users = MainUser::orderByDesc($order)
        ->offset($offset)
        ->limit(20)
        ->get();
        
        return MainUserResource::collection($users);
    }
  
    
    public function one(MainUser $user)
    {
        return new MainUserResource($user);   
    }

    public function followers(User $user)
    {

        $main_user = MainUser::find($user->id);
        $followers = $main_user->followers;

        return MainUserResource::collection($followers);

    }
 
    public function followings(User $user)
    {

        $main_user = MainUser::find($user->id);
        $followings_user = $main_user->followings_user;
        $followings_publisher = $main_user->followings_publisher;

        return [
            'users' => MainUserResource::collection($followings_user),
            'publishers' => MainPublisherResource::collection($followings_publisher),
        ];


    }



}
