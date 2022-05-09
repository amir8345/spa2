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

        $user_shelves = $this->shelves->pluck('id');

        $book_count = DB::table('book_shelf')
        ->whereIn('shelf_id' , $user_shelves->toArray)
        ->count();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'book_count' => $book_count,

            $this->mergeWhen(request()->path == 'api/user' , [

                'posts' => PostResource::collection($this->posts),
                
                
            ]),

        ]
    }
}
