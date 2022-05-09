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
        
        if(get_class($this->resource) == 'App\Models\Publisher'){
            
            return [ 
                'type' => 'publisher',
                'id' => $this->id,
                'name' => $this->name,
            ];
        }
        
        
        // user is just a normal user
        $type = 'user';
        $id = $this->id;
        
        // user is a publisher which had signed up
        if ($this->publisher) {
            $type = 'publisher';
            $id = $this->publisher->publisher_id;
        }
        
        // user is a contributor who had signed up
        if ($this->contributor) {
            $type = 'contributor';
            $id = $this->contributor->contributor_id;
        }
        
        return [
            'type' => $type,
            'id' => $id,
            'name' => $this->name,
        ];
        
    }
}
