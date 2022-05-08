<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
                
        $user_data = [
            ['user_id' , '=' , $request->user()->id ],
            ['liked_type' , '=' , $request->type ],
            ['liked_id' , '=' , $request->id ],
        ];
        
        
        if (DB::table('likes')->where($user_data)->delete() == 1) {
            return 'deleted successfully';
        }
        
        DB::table('likes')->insert($user_data);
    }
}
