<?php

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login' , [LoginController::class , 'login']);
Route::post('/login/code_verification' , [LoginController::class , 'check_verificaion_code']);
Route::post('/login/set_username_password' , [LoginController::class , 'set_username_and_password']);