<?php

namespace App\Http\Resources;


use App\Http\Resources\TagResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PublisherResource;
use App\Http\Resources\ContributorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MainBookResource extends JsonResource
{
    /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    */
    public function toArray($request)
    {
        
        $info = null;
        
        if ($request->routeIs('book')) {
            
            $info = [
                'title2' => $this->title2,
                'lang' => $this->lang,
                'city' => $this->city,
                'age' => $this->age,
                'format' => $this->format,
                'size' => $this->size,
                'weight' => $this->weight,
                'cover' => $this->cover,
                'paper' => $this->paper,
                'pages' => $this->pages,
                'colorful' => $this->colorful,
                'binding' => $this->binding,
                'about' => $this->about,
                'tags' => TagResource::collection($this->tags),
                'numbers' => [
                    'want' =>  $this->want,
                    'read' =>  $this->read,
                    'reading' =>  $this->reading,
                    'shelves' => $this->shelves->count(),
                ],
            ];
        }
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'isbn' => $this->isbn,
            'publisher' => new PublisherResource($this->publisher),
            'contributors' => [
                'writers' => ContributorResource::collection($this->writers),
                'translators' => ContributorResource::collection($this->translators),
                'editors' => ContributorResource::collection($this->editors),
            ],
            'info' => $info,
            'score' => $this->score
        ];
        
    }
    
}
