<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\Book\Books;

class BookController extends Controller
{
    public function get_all_books(Request $request)
    {
        $books = Book::offset( ( $request->page - 1 ) * 20)->limit(20)->get();
        return Books::collection($books);
    }

    


}
