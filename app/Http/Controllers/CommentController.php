<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use App\Models\MainBook;
use App\Models\MainUser;
use Illuminate\Http\Request;
use App\Models\MainPublisher;
use App\Models\MainContributor;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentParentResource;

class CommentController extends Controller
{

    public function get_comments($type , $id , $page)
    {

        $offset = ($page - 1 ) * 20;

        if ($type == 'book') {
            $model = MainBook::find($id);
        }
        if ($type == 'publisher') {
            $model = MainPublisher::find($id);
        }
        if ($type == 'contributor') {
            $model = MainContributor::find($id);
        }
        if ($type == 'user') {
            $model = MainUser::find($id);
        }

        $comments = $model->comments()->offset($offset)->limit(20)->get();

        return CommentResource::collection($comments);
    }




    public function add(Request $request)
    {
        $new_comment_id = DB::table('comments')->insertGetId([
            'user_id' => $request->user()->id,
            'commented_type' => $request->type,
            'commented_id' => $request->id,
            'body' => $request->body,
        ]);

        if (! $new_comment_id) {
            return 'did not insert';
        }

        return 'inserted successfully';

    }

    public function delete(Comment $comment)
    {

        if ($comment->user_id != request()->user()->id) {
            return 'you are not authorized to delete';
        }

        if ($comment->delete() == 1) {
            return 'comment deleted successfully';
        } 

        return 'something is wronge';
    }


    public function update(Comment $comment)
    {

        if ($comment->user_id != request()->user()->id) {
            return 'you are not authorized to update';
        }

        $user_info = [
            'body' => request()->body,
        ];

        if ($comment->update($user_info) == 1) {
            return 'updated successfully';
        }

        return 'something is wronge';
    }


    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }


    public function comments_by(User $user , $page)
    {
        
        $offset = ($page - 1 ) * 20;
        $main_user = MainUser::find($user->id);
    
        $comments = $main_user->comments_by()
        ->offset($offset)
        ->limit(20)
        ->get();

        return CommentResource::collection($comments);

    }


}
