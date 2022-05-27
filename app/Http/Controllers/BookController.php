<?php

namespace App\Http\Controllers;

use App\Models\Shelf;
use App\Models\MainBook;
use App\Models\Publisher;
use Illuminate\Http\Request;
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
    

    public function add_to_shelf(Request $request)
    {

        $new_book_shelf = DB::table('book_shelf')->insertGetId([
            'book_id' => $request->book_id,
            'shelf_id' => $request->shelf_id
        ]);

        if (! $new_book_shelf) {
            return 'something is wronge';
        }

        return 'book inserted successfully';

    }


    public function remove_from_shelf(Request $request)
    {
        $deleted_book_shelf = DB::table('book_shelf')
        ->where('book_id' , $request->book_id)
        ->where('shelf_id' , $request->shelf_id)
        ->delete();

        if ( $deleted_book_shelf != 1) {
            return 'could not delete book from shelf';
        }

        return 'book deleted from shelf successfully';
    }


    public function update_book_status(Request $request)
    {

        $updated_book_shelf = DB::table('book_shelf')
        ->where('book_id' , $request->book_id)
        ->where('shelf_id' , $request->shelf_from)
        ->update(['shelf_id' => $request->shelf_to]);

        if ($updated_book_shelf != 1) {
            return 'could not update book status';
        }

        return 'book status updated successfully';
        
    }

    // show readers who scored this book and current user's score
    public function scores(MainBook $book)
    {

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

    // show books which are inside a shelf
    public function shelf_books(Shelf $shelf , $order , $page)
    {
        $offset = ($page - 1) * 20;
        
        $asc_desc = 'desc';

        if ($order == 'title') {
            $asc_desc = 'asc';
        }

        $books = $shelf->books()
        ->orderBy($order , $asc_desc)
        ->offset($offset)
        ->limit(20)
        ->get();

        return MainBookResource::collection($books);
        
    }
    
    
    public function publisher_books(MainPublisher $publisher , $order , $page)
    {
        $offset = ($page - 1) * 20;
        
        $asc_desc = 'desc';

        if ($order == 'title') {
            $asc_desc = 'asc';
        }

        $books = $publisher->books()
        ->orderBy($order , $asc_desc)
        ->offset($offset)
        ->limit(20)
        ->get();

        return MainBookResource::collection($books);

    }


    public function contributor_books(MainContributor $contributor , $type , $order , $page)
    {
        $offset = ($page - 1) * 20;
        
        $asc_desc = 'desc';

        if ($order == 'title') {
            $asc_desc = 'asc';
        }

        $books = $contributor->books($type)
        ->orderBy($order , $asc_desc)
        ->offset($offset)
        ->limit(20)
        ->get();

        return MainBookResource::collection($books);
    }


 


}

