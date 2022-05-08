<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentParentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
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

    public function delete(Request $request)
    {
        $post = Comment::find($request->id);

        if ($post->user_id != $request->user()->id) {
            return 'you are not authorized to delete';
        }

        if ($post->delete() == 1) {
            return 'deleted successfully';
        } 

        return 'something is wronge';
    }


    public function update(Request $request)
    {
        $post = Comment::find($request->id);

        if ($post->user_id != $request->user()->id) {
            return 'you are not authorized to update';
        }

        $user_info = [
            'body' => $request->body,
        ];

        if ($post->update($user_info) == 1) {
            return 'updated successfully';
        }

        return 'something is wronge';
    }


    public function show(Comment $comment)
    {
        return [
            'comment' =>  new CommentResource($comment),
            'comments' => CommentResource::collection($comment->comments),
            'parent' => new CommentParentResource($this->parent)
        ];
    }


}
