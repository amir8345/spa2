<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Shelf;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BookResource;


class BookController extends Controller
{
    public function all($order , $page)
    {

        // most want_to_read and had_read books
        if ($order == 'read' || $order == 'want') {

            $requested_books = DB::table('most_popular_books')
            ->select('book_id')
            ->where('shelf_name' , $order)
            ->orderByDesc('number') 
            ->get();
        }

        // most comments_and_posts books
        if ($order == 'dibated') {
           
            $requested_books = DB::table('most_dibated_books')
            ->orderByDesc('num')
            ->get();
        }

        // newest books
        // TODO: we should write this code after book_publishes table has data
        






        // make orderByRaw string ready for sql
        $requested_order = ' ';

        foreach ($requested_books as $key => $value) {
            
            $requested_order .= 'id = ' . $value->book_id . ' DESC ';

            if ($key != count($requested_books) - 1) {
                $requested_order .= ' , ';
            }
        }

        $books = Book::orderByRaw($requested_order)
        ->offset( ( $page - 1 ) * 20)
        ->limit(20)
        ->get();

        return BookResource::collection($books);
    }


    public function one(Book $book)
    {
        return new BookResource($book);
    }
    


    public function add_to_shelf(Book $book , Shelf $shelf)
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


    public function remove_from_shelf(Book $book , Shelf $shelf)
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


    public function update_book_shelf(Book $book , Shelf $shelf_to , Shelf $shelf_from)
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

    // show readers who scored this book . show current user at the topp
    public function scores(Book $book)
    {
        return $book->scores()
        ->orderByRaw('user_id = ' . request()->user()->id . ' desc');
    }

    public function same_publisher_books(Book $book)
    {
        $publisher = $book->publisher;
        return $publisher->books->limit(5)->get();
    }

    public function same_writer_books(Book $book)
    {
        $writers = $book->writers;

        // $book = DB::table('books')->whereIn('id' , )
        
        // return BookResource::collection(  )
    }
    
    // show shelves that this book is inside them
    public function shelves(Book $book)
    {
        return $book->shelves;
    }
    
    
    public function publisher_books(Publisher $publisher , $order , $page)
    {
        
        $offset = ($page - 1 ) * 20;
        
        if ($order == 'date') {
            $books = $publisher->books()
            ->latest()
            ->
        }
        
        if ($order == 'alphabet') {
            $books = $publisher->books
            ->orderBy('name')
        }
   
        if ($order == 'score') {
            $books = DB::table('best_books_by_score')
            ->whereIn('book_id' , $publisher->books->pluck('id')->toArray())
        }

        if ($order == 'read') {
            $books = DB::table('most_popular_books')
            ->whereIn('book_id' , $publisher->books->pluck('id')->toArray())
        }



        $books = Book::whereIn('id' , $requested_books_ids)
        ->offset( ( $page - 1 ) * 20)
        ->limit(20)
        ->get();
        
        
        return BookResource::collection($books);
    }


    public function get_books($order , $page)
    {




    }


}

