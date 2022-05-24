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
        
        $contributor_types = ['writer' , 'translator' , 'editor'];
        
        foreach ($contributor_types as $type) {
            $contributors[$type] = MainContributorResource::collection($this->contributors($type)->get());
        }


        // $current_user_score = request()->user()
        //                     ->scores()
        //                     ->where('book_id' , $this->id)
        //                     ->value('score');
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'isbn' => $this->isbn,
            'publisher' => new MainPublisherResource($this->publisher),
            'contributors' => $contributors,
            'numbers' => [
                'want' =>  $this->want,
                'read' =>  $this->read,
                'reading' =>  $this->reading,
                'debate' => $this->debate,
                'shelves' => $this->shelves->count(),
                'score' => $this->score,
            ],
            $this->mergeWhen(request()->routeIs('book') , function() {
                return [
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
            })
        ];
        
    }
    
}
