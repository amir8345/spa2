<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Http\Resources\RoleResource;
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

        $type = 'contributor';
        $id = $this->id;

        if ($this->user) {
            $type = 'user';
            $id = $this->user->user_id;
        }

        return [
            'type' => $type,
            'id' => $id,
            'name' => $this->name,

            $this->mergeWhen($request->routeIs('contibutor') , [
                // info that should be displayed on contributor page only
                // ...

            ]),
        ];
    }
}

