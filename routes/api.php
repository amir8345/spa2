<?php

use App\Models\Tag;
use App\Models\Book;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\Crawler\BookResources;
use App\Http\Resources\Book\Book AS ResourceBook;

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

// book
Route::get('/books' , [BookController::class , 'all_books']);
Route::get('/book' , [BookController::class , 'one_book']);

// contributor 
Route::get('/contributors' , [ContributorController::class , 'all_contributors']);
Route::get('/contributor' , [ContributorController::class , 'one_contributor']);

// publisher
Route::get('/publisher' , [PublisherController::class , 'one_publisher']);

// like
Route::post('/like' , [LikeController::class]);

// post
Route::post('/post/add' , [PostController::class , 'add']);
Route::post('/post/delete' , [PostController::class , 'delete']);
Route::post('/post/update' , [PostController::class , 'update']);
Route::post('/post/show/{post}' , [PostController::class , 'show']);

// comment
Route::post('/comment/add' , [CommentController::class , 'add']);
Route::post('/comment/delete' , [CommentController::class , 'delete']);
Route::post('/comment/update' , [CommentController::class , 'update']);

// shelf
Route::post('/shelf/add' , [ShelfController::class , 'add']);
Route::post('/shelf/delete' , [ShelfController::class , 'delete']);
Route::post('/shelf/update' , [ShelfController::class , 'update']);
Route::get('/shelf/show/{shelf}' , [ShelfController::class , 'show']);


