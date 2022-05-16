<?php

namespace App\Http\Controllers;

use App\Models\Shelf;
use App\Models\MainBook;
use App\Models\Publisher;
use App\Models\MainPublisher;
use App\Models\MainContributor;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ScoreResource;
use App\Http\Resources\ShelfResource;
use App\Http\Resources\MainBookResource;

class BookController extends Controller
{

    public function get_books($order , $page)
    {

        $offset = ($page - 1) * 20;

        $books = MainBook::orderByDesc($order)
        ->offset($offset)
        ->limit(20)
        ->get();

        return MainBookResource::collection($books);

    }


    public function one(MainBook $book)
    {
        return new MainBookResource($book);
    }
    



    public function add_to_shelf(MainBook $book , Shelf $shelf)
    {
        $new_book_shelf = DB::table('book_shelf')->insertGetId([
            'book_id' => $book->id,
            'shelf_id' => $shelf->id
        ]);

        if (! $new_book_shelf) {
            return 'something is wronge';
        }

        return 'book inserted successfully';

    }


    public function remove_from_shelf(MainBook $book , Shelf $shelf)
    {
        $book_shelf = DB::table('book_shelf')
        ->where('book_id' , $book->id)
        ->when('shelf_id' , $shelf->id)
        ->first();

        if ($book_shelf->delete() != 1) {
            return 'could not delete book from shelf';
        }

        return 'book deleleted from shelf successfully';
    }


    public function update_book_shelf(MainBook $book , Shelf $shelf_to , Shelf $shelf_from)
    {

        $updated_book_shelf = DB::table('book_shelf')
        ->where('book_id' , $book->id)
        ->where('shelf_id' , $shelf_from->id)
        ->update(['shelf_id' => $shelf_to]);

        if ($updated_book_shelf != 1) {
            return 'could not update book status';
        }

        return 'book status updated successfully';
        
    }

    // show readers who scored this book and current user's score
    public function scores(MainBook $book)
    {

        // $current_user_score = request()->user()
        //                     ->scores()
        //                     ->where('book_id' , $book->id)
        //                     ->value('score');

        $scores = ScoreResource::collection($book->scores); 

        return ['current_user_score' => '$current_user_score' , 'scores' => $scores];
    }

    // TODO : wating for books tags to be completed
    public function same_publisher_books(MainBook $book)
    {
    }
    public function same_writer_books(MainBook $book)
    {
    }
    public function same_books()
    {
    }
    
    // show shelves that this book is inside them
    public function shelves(MainBook $book)
    {
        return ShelfResource::collection($book->shelves);
    }
    
    
    public function publisher_books(MainPublisher $publisher , $order , $page)
    {
        $offset = ($page - 1) * 20;

        $books = $publisher->books()
        ->orderByDesc($order)
        ->offset($offset)
        ->limit(20)
        ->get();
        
        return MainBookResource::collection($books);

    }


    public function contributor_books(MainContributor $contributor , $order , $page)
    {
        return MainBookResource::collection($contributor->books);
    }


 


}

