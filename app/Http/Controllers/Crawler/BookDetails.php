<?php

namespace App\Http\Controllers\Crawler;

use Illuminate\Http\Request;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Controllers\Crawler\SendHttpRequest;

class BookDetails extends Controller
{

    public $new_books;
    
    public function get_crawlable_books()
    {
        $this->new_books = DB::table('book_urls')
        ->orderByDesc('last_crawled_at')
        ->limit(10)
        ->get();

        $this->crawl_new_books();

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
            
            $this->search_book_in_nl($book_details);
            // break;
        }
        
        
    }
    
    
    public function search_book_in_nl(array $book_details)
    {

        $res = Http::asForm()->post('https://opac.nlai.ir/opac-prod/search/bibliographicAdvancedSearchProcess.do' , [
            'advancedSearch.simpleSearch[0].value' => $book_details['title'],
            'advancedSearch.simpleSearch[1].value' => $this->resource_tag->resource->persian_name,
            'classType' => 0,
            'command' => 'I',
            'advancedSearch.simpleSearch[0].tokenized' => true
        ]);
        // echo $res->body();

        $this->find_best_match_in_nl_search_result($res->body() , $book_details);

    }


    public function find_best_match_in_nl_search_result(string $body , $book_details)
    {
        
        $crawler = new Crawler($body);
        
        $candidate_rows = [];

        $crawler->filter('#table tr')->each(function(Crawler $row , $i){
            
                $title = $this->encode_to_iso_8859_1($row->filter('#td2')->text());
                $writer = $this->encode_to_iso_8859_1($row->filter('#td3')->text());
                $year = $this->encode_to_iso_8859_1($row->filter('#td4')->text());
                $link = $row->filter('#td2 a')->attr('href');
            
                $candidate_rows[$i]['title'] = $title;    
                $candidate_rows[$i]['writer'] = $writer;    
                $candidate_rows[$i]['year'] = $year;    
                $candidate_rows[$i]['link'] = $link;    

        }); 

        foreach ($candidate_rows as $row) {

            if ( strpos($row['title'] , $book_details['title']) === false ) {
                continue;
            }
           
            $priority = 'B';

            foreach (explode(' ' , $book_details['writer']) as $value) {
                if ( strpos($row['writer'] , $value) !== false) {
                    $priority = 'A';
                }
            }

            $sorted_rows_according_to_priority_in_nl_search[$priority][$row['year']] = [ 
                'link' => $row['link'],
            ];

        }

        ksort($sorted_rows_according_to_priority_in_nl_search);

        if ($sorted_rows_according_to_priority_in_nl_search['A']) {
            ksort($sorted_rows_according_to_priority_in_nl_search['A']);
        }

        if ($sorted_rows_according_to_priority_in_nl_search['B']) {
            ksort($sorted_rows_according_to_priority_in_nl_search['B']);
        }

        $nl_url = $sorted_rows_according_to_priority_in_nl_search[0][0];

    }


    public function crawl_book_page_in_nl(string $url)
    {

        
        $send_http_request = new SendHttpRequest();
        $body = $send_http_request($url);
        
        $crawler = new Crawler($body);

        $nl_details = [];

        $crawler->filter('.formcontent')
            ->eq(0)
            ->filter('table table table td')
            ->each(function(Crawler $node , $i) {

                $nl_details[$i]['book_id'] = $this->new_books['book_id'];

                if ($node->attr('width') == "20%") {
                    $name = $this->encode_to_iso_8859_1($node->text());
                    $nl_details[$i]['name'] = $name;
                }

                if ($node->attr('width') == "75%") {
                    $description = $this->encode_to_iso_8859_1($node->text());
                    $nl_details[$i]['description'] = $description;
                }
               
            });

            $nl_details[] = [ 
                'book_id' => $this->new_books['book_id'],
                'name' => 'main_address',
                'description' => $crawler->filter('.formcontent')->eq(1)->filter('input')->attr('value')
            ];    
        
            $this->send_data_to_database($nl_details , 'book_nl');
            $this->send_data_to_database(end($nl_details) , 'book_urls');

    }


    public function send_data_to_database(array $array , string $table_name)
    {

        DB::table($table_name)->insert($array);

    }


    public function encode_to_iso_8859_1(string $item)
    {
        return iconv("UTF-8" , "ISO-8859-1" , $item);
    }

}
