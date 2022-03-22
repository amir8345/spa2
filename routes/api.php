<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login' , [LoginController::class , 'login']);
Route::get('/login/send_virification_code' , [LoginController::class , 'send_virification_code']);
Route::post('/login/check_verificaion_code' , [LoginController::class , 'check_verificaion_code']);
Route::post('/login/set_username_password' , [LoginController::class , 'set_username_and_password']);
Route::post('/login/password_check' , [LoginController::class , 'password_check']);
Route::post('/login/update_password' , [LoginController::class , 'update_password']);
Route::get('/login/login_with_disposable_code' , [LoginController::class , 'login_with_disposable_code']);