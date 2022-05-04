<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ContributorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd(User::find($this->user->user_id)->followers);
        return [
            'id' => $this->id,
            'name' => $this->name,
            $this->mergeWhen($request->path() == 'api/contributor' , [
                // info that should be displayed on contributor page only
                
                // ...
                
                // info that should be displayed on contributor page 
                // if contributor had signed up in website

                $this->mergeWhen($this->user , [
                    'is_user' => true,
                    'followers' => UserResource::collection(User::find($this->user->user_id)->followers)
                ]),
            ]),
        ];
    }
}
