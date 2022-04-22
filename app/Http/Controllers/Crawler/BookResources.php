<?php

namespace App\Http\Controllers\Crawler;

use App\Models\Book;
use App\Models\ResourceTag;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Controllers\Crawler\SendHttpRequest;

class BookResources extends Controller
{
   
    public $resource_tag;
    public $resource_url;
    public $resource_body;
    public $new_books;
    
    /**
    * extract resource urls that need to be crawled
    *
    * @return array
    */
    public function extract_resource()
    {
        
        $this->resource_tag = ResourceTag::orderByDesc('last_crawled_at')->first();
        
        $this->resource_url = str_replace(['{tag}' , '{num}'] , [$this->resource_tag['tag'] , $this->resource_tag['num']] , $this->resource_tag->resource->url );
        
        $this->crawl_resource();
        
    }
    
    
    
    /**
    * crawl resource urls and add body to resources[]
    *
    * @param  array $resource
    * @return void
    */
    public function crawl_resource()
    {

        $send_http_request = new SendHttpRequest();

        $this->resource_body = $send_http_request($this->resource_url);
        
        $this->extract_new_books_and_insert_them();
    }
    
    
    
    /**
    * extract list of new books that need to be crawled form reosurce page
    *
    * @param  string $body , $filter
    * @return array $new_books
    */
    public function extract_new_books_and_insert_them()
    {
        $crawler = new Crawler($this->resource_body);
        $extracted_books_urls = $crawler->filter($this->resource_tag['filter'])->extract(['href']);
        
        $new_books_counter = 0;
        
        foreach ($extracted_books_urls as $key => $url) {
            
            if ( DB::table('book_urls')->where('url' , $url)->doesntExist() ) {
                
                $book_id = $this->get_book_id();
                $this->new_books[$key]['id'] = $book_id;
                $this->new_books[$key]['url'] = $url;
                
                DB::table('book_urls')->insert([
                        'book_id' => $book_id,
                        'url_name' => $this->resource_tag->resource->kind,
                        'url' => $url,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    $new_books_counter ++;
                }
                
            }
            
            $update_array = [ 'last_crawled_at' => now() ];
            
            if ($new_books_counter > count($extracted_books_urls) / 2 ) {
                $update_array = [ 'num' => $this->resource_tag['num'] + 1 ];
            }
            
            $this->update_resource_tag($update_array);

            
            
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
