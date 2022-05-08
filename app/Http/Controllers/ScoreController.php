<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    public function add_score(Request $request)
    {

        DB::table('score')->upsert([
            'user_id' => $request->user()->id,
            'book_id' => $request->book_id,
            'score' => $request->score
        ] , ['user_id', 'book_id'] , ['score']);

    }
}
