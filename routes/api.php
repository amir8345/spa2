<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Crawler\BookResources;

Route::post('/login' , [LoginController::class , 'login']);
Route::get('/login/send_code' , [LoginController::class , 'send_code']);
Route::post('/login/code_verification' , [LoginController::class , 'code_verification']);
Route::post('/login/set_username_password' , [LoginController::class , 'set_username_password']);
Route::post('/login/password_check' , [LoginController::class , 'password_check']);
Route::post('/login/update_password' , [LoginController::class , 'update_password']);
Route::get('/login/disposable_code' , [LoginController::class , 'disposable_code']);
Route::get('/login/logout' , [LoginController::class , 'logout']);

Route::middleware('auth:sanctum')->get('/profile' , function(){
    return 'profile page';
});


Route::get('/crawl' , [BookResources::class , 'extract_resource']);

