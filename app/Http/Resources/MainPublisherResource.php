<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\SocialMediaResource;
use App\Http\Resources\PublisherResource AS tr;
use Illuminate\Http\Resources\Json\JsonResource;

class MainPublisherResource extends JsonResource
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
        
        return [
            'type' => $type,
            'id' => $id,
            'name' => $this->name,
            'follower' => $this->follower,
            $this->mergeWhen($request->routeIs('publisher') , function() {
                
                $contributor_types = ['writer' , 'translator' , 'editor'];
                foreach ($contributor_types as $type) {
                    $numbers[$type] = $this->contributors()->where('action' , $type)->count();
                }

                $numbers['book'] = $this->book;
                
                return [
                    'social_medias' => SocialMediaResource::collection($this->social_medias),
                    'numbers' => $numbers,
                ];
            })
        ];
        
    }
}
