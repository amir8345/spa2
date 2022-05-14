<?php

namespace App\Http\Resources;

use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'writer' => new UserResource($this->writer),
            'body' => $this->body,
            'likes_num' => $this->likes->count(),
            'comments_num' => $this->comments->count(),
            
            $this->mergeWhen(request()->routeIs('comment') , function() {
                
                $parent = null;

                if ( get_class($this->parent) == 'App\Models\Post' ) {
                    $parent = new PostResource($this->parent);
                }
                
                if ( get_class($this->parent) == 'App\Models\Comment' ) {
                    $parent = new CommentResource($this->parent);
                }

                return [
                    'parent' => $parent,
                    'comments' => CommentResource::collection($this->comments)
                ];

            })
        ];

    }
}
