<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Post;
use App\Models\User;
use App\Models\MainBook;
use App\Models\MainUser;
use App\Models\Publisher;
use App\Models\Contributor;
use Illuminate\Http\Request;
use App\Models\MainPublisher;
use App\Models\MainContributor;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;

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

        $post = Post::find($request->post_id);
        
        if ($post->delete() != 1) {
            return 'could not delete post';
        } 
        
        return 'deleted successfully';
    }
    
    
    public function update(Request $request)
    {
        
        $post = Post::find($request->post_id);

        if ($request->has('title')) {
            $user_info['title'] = $request->title;
        }
        
        if ($request->has('body')) {
            $user_info['body'] = $request->body;
        }

        if ($post->update($user_info) != 1) {
            return 'could not update post';
        }
        
        return 'updated successfully';
    }


    public function show(Post $post)
    {
        return new PostResource($post);
    }

    public function get_posts($type , $id , $page)
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

        $posts = $model->posts()->offset($offset)->limit(20)->get();

        return PostResource::collection($posts);
    }

    public function posts_by(User $user , $page)
    {
        $offset = ($page - 1 ) * 20;

        $main_user = MainUser::find($user->id);
    
        $posts = $main_user->posts_by()
        ->offset($offset)
        ->limit(20)
        ->get();

        return PostResource::collection($posts);

    }

}
