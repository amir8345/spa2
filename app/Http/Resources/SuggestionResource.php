<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuggestionResource extends JsonResource
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
            'user_id' => $this->user_id,  
            'book_id' => $this->book_id,  
            'receiver' => $this->receiver,  
            'reason' => $this->reason,  
            'updated_at' => $this->updated_at,  
        ];
    }
}
