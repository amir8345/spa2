<?php

use App\Http\Controllers\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/signup/mobile' , [SignupController::class , 'mobile']);
Route::post('/signup/code_verification' , [SignupController::class , 'code_verification']);
Route::post('/signup/set_username_password' , [SignupController::class , 'set_username_password']);

Route::middleware('auth:sanctum')->get('/profile' , function(){
    return 'this is profile';
});


