<?php

namespace App\Http\Resources;

use App\Http\Resources\PublisherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    */
    public function toArray($request)
    {
        
        // calculating book count
        // $user_shelves = $this->shelves->pluck('id');
        // $book_count = DB::table('book_shelf')
        // ->whereIn('shelf_id' , $user_shelves->toArray)
        // ->count();
        
        $info = null;

        if (request()->path == 'api/user') {
            $info = [
                'posts_by' => PostResource::collection($this->posts_by),
                'posts_on' => PostResource::collection($this->posts_on),
                'comments_by' => CommentResource::collection($this->comments_by),
                'comments_on' => CommentResource::collection($this->comments_on),
                'followers' => UserResource::collection($this->followers),
                'followings_user' => UserResource::collection($this->followings_user),
                'followings_publisher' => PublisherResource::collection($this->followings_publisher),
                'scores' => ScoreResource::collection($this->socres)
            ];
        }
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'info' => $info 
            // 'book_count' => $book_count,
            ];
        }
    }
    