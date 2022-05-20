<?php

namespace App\Http\Resources;

use App\Http\Resources\BookResource;
use App\Http\Resources\MainUserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ShelfResource extends JsonResource
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
            'creator' => new MainUserResource($this->user), 
            'num' => $this->books->count(),
        ];
         
    }
}
