<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\PublisherResource AS yu;
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
                    'followers' => UserResource::collection($this->followers),
                    
                    // $this->mergeWhen($this->user , function() {
                    //     return [ 
                    //         'followings_user' => UserResource::collection(User::find($this->user->user_id)->followings_user),
                    //         'followings_publisher' => $this->collection(User::find($this->user->user_id)->followings_publisher),
                            
                    //     ];
                        
                    // }),
                ];
            }),
            
            
        ];
        // info that should be displayed if publisher had signed in
        
    }
}
