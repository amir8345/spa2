<?php

namespace App\Http\Resources;

use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\MainUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'writer' => new MainUserResource($this->writer),
            'title' => $this->title,
            'body' => $this->body,
            'likes_num' => $this->likes->count(),
            'comments_num' => $this->comments->count(),
            'comments' => $this->when(
                request()->routeIs('post') , 
                CommentResource::collection($this->comments) 
            )
        ];
    }
}
