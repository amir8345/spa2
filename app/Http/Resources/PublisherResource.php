<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\PublisherResource AS tr;
use Illuminate\Http\Resources\Json\JsonResource;

class PublisherResource extends JsonResource
{
    /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    */
    public function toArray($request)
    {

        $type = 'publisher';
        $id = $this->id;

        if ($this->user) {
            $type = 'user';
            $id = $this->user->user_id;
        }

        $info = null;

        if ($request->routeIs('publisher')) {
            $info = [
                'followers' => UserResource::collection($this->followers),
                'books_num' => $this->books->count(),
                'contributors_num' => [
                    'writer' => $this->contributors->where('action' , 'writer')->count(),
                    'editor' => $this->contributors->where('action' , 'editor')->count(),
                    'translator' => $this->contributors->where('action' , 'translator')->count(),
                ],
            ];
        }
        
        return [
            'type' => $type,
            'id' => $id,
            'name' => $this->name,
            'info' => $info,
        ];
        
    }
}
