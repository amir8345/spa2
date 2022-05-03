<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Tag;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Publisher\Books AS PublisherBooks;
use App\Http\Resources\Contributor\Books as ContributorBooks;

class Books extends JsonResource
{

    public $preserveKeys = true;

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
            'title' => $this->title,
            'isbn' => $this->isbn,
            'publisher' => new PublisherBooks($this->publisher),
            'writers' => ContributorBooks::collection($this->writers),
            'translators' => ContributorBooks::collection($this->translators),
            'editors' => ContributorBooks::collection($this->editors),
            'tags' => Tag::collection($this->tags),
            'numbers' => [
                'want_to_read' =>  $this->want_to_read(),
                'had_read' =>  $this->had_read(),
                'reading' =>  $this->reading(),
            ],
            'score' => round($this->score() , 1) 
           
        ];
    }
}
