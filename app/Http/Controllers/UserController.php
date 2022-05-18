<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MainUser;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\MainUserResource;

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

}
