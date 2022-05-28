<?php

namespace App\Http\Controllers;

use App\Http\Resources\SuggestionResource;
use App\Models\MainUser;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuggestionController extends Controller
{
    public function add(Request $request)
    {
        $user_info = [];

        $request->collect('receivers')->each(function ($receiver , $key) use ($request , &$user_info) {

            $user_info[$key]['user_id'] = request()->user()->id;
            $user_info[$key]['book_id'] = $request->book_id;
            $user_info[$key]['receiver'] = $receiver;
            $user_info[$key]['reason'] = $request->reason;
            
        });

        $new_suggestion = DB::table('suggestions')->insert($user_info);
        
        if (! $new_suggestion) {
            return 'could not add suggestion';
        }

        return 'book suggested successfully';
    }

    public function delete(Request $request)
    {
        $suggestion = Suggestion::find($request->suggestion_id);

        if ($suggestion->delete() != 1) {
            return 'could not delete suggestion';
        }

        return 'suggestion deleted successfully';

    }

    public function update(Request $request)
    {
        $suggestion = Suggestion::find($request->suggestion_id);

        if ($request->has('book_id')) {
            $user_info['book_id'] = $request->book_id;
        }
        
        if ($request->has('receiver')) {
            $user_info['receiver'] = $request->receiver;
        }
        
        if ($request->has('reason')) {
            $user_info['reason'] = $request->reason;
        }

        if ($suggestion->update($user_info) != 1) {
            return 'could not update suggestion';
        }
        
        return 'updated successfully';
        
    }
    

    public function all(MainUser $user , $page)
    {
        $offset = ($page - 1) * 20;

        $suggestions = $user->suggestions()->offset($offset)->limit(20)->get();
        return SuggestionResource::collection($suggestions);
    }

}
