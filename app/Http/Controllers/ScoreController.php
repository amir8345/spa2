<?php

namespace App\Http\Controllers;

use App\Http\Resources\ScoreResource;
use App\Models\Book;
use App\Models\MainUser;
use App\Models\User;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    public function add_or_update_score(Request $request)
    {

        DB::table('score')->upsert([
            'user_id' => $request->user()->id,
            'book_id' => $request->book_id,
            'score' => $request->score
        ] , ['user_id', 'book_id'] , ['score']);

    }

    public function delete(Request $request)
    {

        $score = Score::where('book_id' , $request->book_id)->first();
       
        if ($score->delete() != 1 ) {
            return 'could not delete score';
        }

        return 'score deleted successfully';
    }

    public function user_scores(User $user)
    {
        $main_user = MainUser::find($user->id);
        $scores = $main_user->scores;

        return ScoreResource::collection($scores);
        
    }

  


}
