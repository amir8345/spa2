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

    public $book;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $book)
    {
        $this->book = $book;
    }

    /**
     * Execute the job
     *
     * @return void
     */
    public function handle()
    {
        $book_details = new BookDetails($this->book);
        $book_details->crawl_new_book();
    }
}
