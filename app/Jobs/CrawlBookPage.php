<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\Crawler\BookDetails;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class CrawlBookPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $resource_name;
    public $baseURI;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $url , string $resource_name , string $baseURI)
    {
        $this->url = $url;
        $this->resource_name = $resource_name;
        $this->baseURI = $baseURI;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $book_details = new BookDetails($this->url , $this->resource_name , $this->baseURI);
        $book_details->crawl_new_book();
    }
}
