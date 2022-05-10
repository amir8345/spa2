<?php

namespace App\Http\Resources;

use App\Http\Resources\BookResource;
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

        if (request()->routeIs('library')) {
            $books = $this->books->limit(5)->get();
        }
        
        if (request()->routeIs('shelf')) {
            $books = $this->books;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'num' => $this->books->count,
            'books' => BookResource::collection($books),
        ];
        
    }
}
