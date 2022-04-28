<?php

namespace App\Http\Controllers\Crawler;

use App\Models\Book;
use App\Models\ResourceTag;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Controllers\Crawler\ToolClassess\SendHttpRequest;
use App\Jobs\CrawlBookPage;

class BookResources extends Controller
{
   
    public $resource_tag;
    
    public function extract_resource()
    {

        $this->resource_tag = ResourceTag::orderByDesc('last_crawled_at')->first();
        
        $resource_url = str_replace(['{tag}' , '{num}'] , [$this->resource_tag['tag'] , $this->resource_tag['num']] , $this->resource_tag->resource->url );
        
        $this->crawl_resource($resource_url);

    }
    
    
    
    public function crawl_resource(string $resource_url)
    {

        $send_http_request = new SendHttpRequest();
        $response = $send_http_request($resource_url);

        if ($response['status'] == '404') {
            
            $this->update_resource_tag([
                'num' => $this->resource_tag['num'] - 1 ,
                'last_crawled_at' => now()
            ]);
         
            die();  
            
        }
        
        $resource_body = $response['body'];
        $this->extract_new_books_and_insert_them($resource_body);
    }
    
    
    
    public function extract_new_books_and_insert_them(string $resource_body)
    {
        $crawler = new Crawler($resource_body);
        $extracted_books_urls = $crawler->filter($this->resource_tag['filter'])->extract(['href']);
        
        $new_books_counter = 0;
        
        foreach ($extracted_books_urls as $key => $book_url) {
            
            $book['id'] = $this->get_book_id();
            $book['url'] = $book_url;
            $book['resource_name'] =$this->resource_tag->resource->name;
            $book['resource_persian_name'] =$this->resource_tag->resource->persian_name;
            $book['resource_kind'] =$this->resource_tag->resource->kind;

            // make a dispatch 
            dispatch( new CrawlBookPage( $book ) );

            if ( DB::table('book_urls')->where('url' , $book_url)->doesntExist() ) {
                
                
                // DB::table('book_urls')->insert([
                //         'book_id' => $book_id,
                //         'url_name' => $this->resource_tag->resource->kind,
                //         'url' => $book_url,
                //         'created_at' => now(),
                //         'updated_at' => now()
                //     ]);
                    
                    $new_books_counter ++;
            }
                
        }
            
            $update_array = [ 'last_crawled_at' => now() ];
            
            if ($new_books_counter > count($extracted_books_urls) / 2 ) {
                $update_array = [ 'num' => $this->resource_tag['num'] + 1 ];
            }
            
            // $this->update_resource_tag($update_array);
            
        }
        
        
        
        public function update_resource_tag(array $update_array)
        {
            DB::table('resource_tags')
            ->where('id' , $this->resource_tag['id'])
            ->update($update_array);
        }

         /**
        * insert empty row to book table and return book id
        *
        * @return string $book_id
        */
        public function get_book_id()
        {
            return Book::insertGetId(['title' => '']);
        }

        
     



}
