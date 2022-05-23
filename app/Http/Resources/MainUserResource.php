<?php

namespace App\Http\Resources;

use App\Http\Resources\PublisherResource;
use App\Http\Resources\MainPublisherResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MainUserResource extends JsonResource
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
            'name' => $this->name,
            'book' => $this->book,
            'follower' => $this->follower,
            $this->mergeWhen(request()->routeIs('user') , [
                'numbers' => [
                        'post' => $this->posts_by->count(),
                        'comment' => $this->comments_by->count(),
                        'score' => $this->scores->count(),
                        'like' => $this->likes->count(),
                ],
                'social_medias' => SocialMediaResource::collection($this->social_medias) 
            ])
        ];
                
        }
    }
    