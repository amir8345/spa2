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
                // break;
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
            // echo $res->body();

            $this->find_best_match_in_nl_search_result($res->body() , $title);

        }


        public function find_best_match_in_nl_search_result(string $body , $book_title)
        {
            
            $crawler = new Crawler($body);

            $crawler->filter('#table tr')->each(function(Crawler $row , $i) use ($book_title){

                $title = $row->filter('#td2')->text();
                $encoded_title =  $this->encode_to_iso_8859_1($title);
            
                if ( strpos($encoded_title , $book_title) !== false ) {
                    echo $encoded_title . '<br>';
                }
                
            }); 
            
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
        
        
       

        public function encode_to_iso_8859_1(string $item)
        {
            return iconv("UTF-8" , "ISO-8859-1" , $item);
        }
        
        
    }
    