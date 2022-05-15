<?php

namespace App\Http\Resources;

use App\Models\Book;
use App\Models\User;
use App\Models\MainBook;
use App\Http\Resources\MainBookResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ScoreResource extends JsonResource
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
            'user' => new UserResource( User::find($this->user_id) ),
            'book' => new MainBookResource( MainBook::find($this->book_id) ),
            'score' => $this->score,
        ];
    }
}
