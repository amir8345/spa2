<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ResourceTag;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerController extends Controller
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
        $this->resource_body = $this->send_http_request($this->resource_url);
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
                
                // DB::table('book_urls')->insert([
                    //     'book_id' => $book_id,
                    //     'url_name' => $this->resource_tag->resource->kind,
                    //     'url' => $url,
                    //     'created_at' => now(),
                    //     'updated_at' => now()
                    // ]);
                    
                    $new_books_counter ++;
                }
                
            }
            
            $update_array = [ 'last_crawled_at' => now() ];
            
            if ($new_books_counter > count($extracted_books_urls) / 2 ) {
                $update_array = [ 'num' => $this->resource_tag['num'] + 1 ];
            }
            
            // $this->update_resource_tag($update_array);
            $this->crawl_new_books();
            
        }
        
        
        
        public function update_resource_tag(array $update_array)
        {
            DB::table('resource_tags')
            ->where('id' , $this->resource_tag['id'])
            ->update($update_array);
        }
        
        
        
        public function crawl_new_books()
        {
            
            $new_books_url_pool = function(Pool $pool) {
                foreach ($this->new_books as $new_book) {
                    $array[] = $pool->get($new_book['url']);
                }
                return $array;
            };
            
            $responses = Http::pool($new_books_url_pool);
            
            require(__DIR__ . '/../CrawlFilters/' . $this->resource_tag->resource->name . '.php');
            
            foreach ($responses as $key => $value) {

                $book_details = book_details($responses[$key]->body() , $this->resource_tag->resource->name);
                $book_tags = book_tags($responses[$key]->body());
                $book_publishes = book_publishes($responses[$key]->body());
                
                $this->search_book_in_nl($book_details['title']);
            }
            
            
        }
        
        
        public function search_book_in_nl(string $title)
        {

            $res = Http::asForm()->post('https://opac.nlai.ir/opac-prod/search/bibliographicAdvancedSearchProcess.do' , [
                'advancedSearch.simpleSearch[0].value' => $title,
                'advancedSearch.simpleSearch[1].value' => $this->resource_tag->resource->persian_name,
                'classType' => 0,
                'command' => 'I',
                'advancedSearch.simpleSearch[0].tokenized' => true
            ]);

            echo $res->body();


        }


        
        /**
        * send http request and return response body
        *
        * @param  string $url
        * @return string $body
        */
        public function send_http_request(string $url)
        {
            $res = Http::get($url);
            return $res->body();
            
        }
        
        
        /**
        * send data to database and return TRUE if successfully inserted
        *
        * @param  array $data
        * @param string $table_name
        * @return bool inserted_successfully
        */
        public function send_data_to_database(array $data , string $table_name)
        {
            DB::table($table_name)->insert($data);
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
    