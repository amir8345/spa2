<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
    * Transform the resource into an array.
    ** this resource accepts two models : Uesr and Publisher
    * 
    
    * @param  \Illuminate\Http\Request  $request
    * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    */
    public function toArray($request)
    {
        
        $type = 'user';

        if(get_class($this->resource) == 'App\Models\MainPublisher'){
            $type = 'publisher';
        }
        
        return [
            'type' => $type,
            'id' => $this->id,
            'name' => $this->name,
        ];
        
    }
}
