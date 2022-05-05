<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

            // user is just a normal user
            $user_type = 'user';
            $id = $this->id;
            
            // user is a publisher which had signed up
            if ($this->publisher) {
                $user_type = 'publisher';
                $id = $this->publisher->publisher_id;
            }
            
            // user is a contributor who had signed up
            if ($this->contributor) {
                $user_type = 'contributor';
                $id = $this->contributor->contributor_id;
            }


        return [
            'user_type' => $user_type,
            'id' => $id,
        ];
    }
}
