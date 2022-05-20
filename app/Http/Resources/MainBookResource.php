<?php

namespace App\Http\Resources;


use App\Http\Resources\TagResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PublisherResource;
use App\Http\Resources\ContributorResource;
use App\Http\Resources\MainPublisherResource;
use App\Http\Resources\MainContributorResource;
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
        // $current_user_score = request()->user()
        //                     ->scores()
        //                     ->where('book_id' , $this->id)
        //                     ->value('score');
        
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
                // 'current_user_score' => $current_user_score,
                
            ];
        }
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'isbn' => $this->isbn,
            'publisher' => new MainPublisherResource($this->publisher),
            'contributors' => [
                'writers' => MainContributorResource::collection($this->writers),
                'translators' => MainContributorResource::collection($this->translators),
                'editors' => MainContributorResource::collection($this->editors),
            ],
            'info' => $info,
            'numbers' => [
                'want' =>  $this->want,
                'read' =>  $this->read,
                'reading' =>  $this->reading,
                'debate' => $this->debate,
                'shelves' => $this->shelves->count(),
                'score' => $this->score,
            ],
        ];
        
    }
    
}
