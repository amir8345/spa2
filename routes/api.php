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
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\ShelfController;
use App\Models\Publisher;

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
Route::get('/books/{order}/{page}' , [BookController::class , 'all'])
->name('books');
Route::get('/book/{book}' , [BookController::class , 'one'])
->name('book');
Route::get('/book/{book}/scores' , [BookController::class , 'scores']);

Route::post('/book/{book}/add_to_shelf/{shelf}' , [BookController::class , 'add_to_shelf']);
Route::post('/book/{book}/remove_from_shelf/{shelf}' , [BookController::class , 'remove_from_shelf']);
Route::post('/book/{book}/update_book_shelf/{shelf_from}/{shelf_to}' , [BookController::class , 'update_book_shelf']);

Route::get('/publisher/{publisher}/books/{order}/{page}' , [BookController::class , 'publisher_books']);
Route::get('/book/{book}/shelves' , [BookController::class , 'shelves']);

// contributor 
Route::get('/contributors/{type}/{order}/{page}' , [ContributorController::class , 'all'])
->name('contributors');
Route::get('/contributor/{contributor}' , [ContributorController::class , 'one'])
->name('contributor');

// publisher
Route::get('/publishers/{order}/{page}' , [PublisherController::class , 'all'])
->name('publishers');
Route::get('/publisher/{publisher}' , [PublisherController::class , 'one'])
->name('publisher');
Route::get('/publisher/{publisher}/{contributor_type}' , [PublisherController::class , 'contributors']);


// like
Route::post('/like' , [LikeController::class , 'toggle_like']);
Route::get('/likes/{type}/{id}' , [LikeController::class , 'likes']);

// post
Route::post('/post/add' , [PostController::class , 'add']);
Route::post('/post/delete/{post}' , [PostController::class , 'delete']);
Route::post('/post/update/{post}' , [PostController::class , 'update']);
Route::post('/post/show/{post}' , [PostController::class , 'show'])
->name('post');
Route::get('/posts/{type}/{id}/{page}' , [PostController::class , 'get_posts']);


// comment
Route::post('/comment/add' , [CommentController::class , 'add']);
Route::post('/comment/{comment}/delete' , [CommentController::class , 'delete']);
Route::post('/comment/{comment}/update' , [CommentController::class , 'update']);
Route::get('/comment/{comment}/show' , [CommentController::class , 'show'])
->name('comment');

// shelf
Route::post('/shelf/add' , [ShelfController::class , 'add']);
Route::post('/shelf/{shelf}/delete' , [ShelfController::class , 'delete']);
Route::post('/shelf/{shelf}/update' , [ShelfController::class , 'update']);
Route::get('/shelf/{shelf}/show/{page}' , [ShelfController::class , 'show'])
->name('shelf');
Route::get('/library/{user}' , [ShelfController::class , 'library'])
->name('library');
Route::get('/user/{user}/shelves' , [ShelfController::class , 'shelves']);

// score
Route::post('/score/add_or_update' , [ScoreController::class , 'add_or_update_score']);
Route::post('/score/{score}/delete' , [ScoreController::class , 'delete']);

