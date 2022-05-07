<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BookResource;


class BookController extends Controller
{
    public function all_books(Request $request)
    {

        // most want_to_read and had_read books
        if ($request->order == 'read' || $request->order == 'want') {

            $requested_books = DB::table('most_popular_books')
            ->select('book_id')
            ->where('shelf_name' , $request->order)
            ->orderByDesc('number') 
            ->get();
        }

        // most comments_and_posts books
        if ($request->order == 'dibated') {
           
            $requested_books = DB::table('most_dibated_books')->orderByDesc('num')->get();
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
        ->offset( ( $request->page - 1 ) * 20)
        ->limit(20)
        ->get();

        return BookResource::collection($books);
    }




    public function one_book(Request $request)
    {
        $book = Book::find($request->id);
        return new BookResource($book);
    }
    


}
