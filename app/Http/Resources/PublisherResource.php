<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Http\Resources\RoleResource;
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
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            
            $this->mergeWhen($request->path() == 'api/publisher', function(){

                return [
                    'followers' => RoleResource::collection($this->followers),
                    
                    $this->mergeWhen($this->user , function() {
                  
                        if (! $this->user) {
                            return null;
                        }
                  
                        return [ 
                            'followings_user' => RoleResource::collection(User::find($this->user->user_id)->followings_user),
                            'followings_publisher' => RoleResource::collection(User::find($this->user->user_id)->followings_publisher),
                            
                        ];
                        
                    }),
                ];
            }),
            
            
        ];
        // info that should be displayed if publisher had signed in
        
    }
}
