<?php

namespace App\Http\Resources;

use App\Http\Resources\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentParentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        $resource = get_class($this->resource);

        if ($resource == 'App\Models\Post') {
            $resource = 'post';
        }
        
        if ($resource == 'App\Models\Comment') {
            $resource = 'comment';
        }

        if(! ($resource == 'post' && $resource == 'comment' ) ){
            return null;
        }

        return [
            'id' => $this->id,
            'type' => $resource,
            'writer' => new RoleResource($this->writer),
            'title' => $this->when($resource == 'post' , $this->title),
            'body' => $this->when($resource == 'comment' , $this->body),
        ];

    }
}
