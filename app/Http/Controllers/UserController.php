<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function all()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    public function one(User $user)
    {
        return new UserResource($user);   
    }

}
