<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Publisher\Books AS PublisherBooks;
use App\Http\Resources\Contributor\Books AS ContributorBooks;
use App\Http\Resources\Post\Book as PostBook;
use App\Http\Resources\Tag;
use Illuminate\Http\Resources\Json\JsonResource;

class Book extends JsonResource
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
            'title' => $this->title,
            'title2' => $this->title2,
            'lang' => $this->lang,
            'city' => $this->city,
            'age' => $this->age,
            'isbn' => $this->isbn,
            'format' => $this->format,
            'size' => $this->size,
            'weight' => $this->weight,
            'cover' => $this->cover,
            'paper' => $this->paper,
            'pages' => $this->pages,
            'colorful' => $this->colorful,
            'binding' => $this->binding,
            'about' => $this->about,
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
            'posts' => PostBook::collection($this->posts),

            'score' => round($this->score() , 1) 
            


        ];
    }
}
