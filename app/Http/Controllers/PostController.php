<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    
    public function add(Request $request)
    {
    
        $new_post_id = DB::table('posts')->insertGetId([
            'user_id' => $request->user()->id,
            'posted_type' => $request->type,
            'posted_id' => $request->id,
            'title' => $request->title,
            'body' => $request->body,
        ]);

        if (! $new_post_id) {
            return 'did not insert';
        }

        return 'inserted successfully';

    }

    public function delete(Request $request)
    {
        $post = Post::find($request->id);

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
        $post = Post::find($request->id);

        if ($post->user_id != $request->user()->id) {
            return 'you are not authorized to update';
        }

        $user_info = [
            'title' => $request->title,
            'body' => $request->body,
        ];

        if ($post->update($user_info) == 1) {
            return 'updated successfully';
        }

        return 'something is wronge';
    }


    public function show(Post $post)
    {
        return [
            'post' =>  new PostResource($post),
            'comments' => CommentResource::collection($post->comments),
        ];
    }


}
