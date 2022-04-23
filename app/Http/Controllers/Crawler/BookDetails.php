<?php

namespace App\Http\Controllers\Crawler;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Controllers\Crawler\SendHttpRequest;

class BookDetails extends Controller
{

    public $url;
    public $resource_name;
    public $baseURI;
    
    
    public function __construct(string $url , string $resource_name , string $baseURI)
    {
        $this->url = $url;
        $this->resource_name = $resource_name;
        $this->baseURI = $baseURI;
    }

    
    public function crawl_new_book()
    {
        
        $send_http_request = new SendHttpRequest();
        $body = $send_http_request($this->url);
        
        $this->get_book_info($body);


        // $re = new $this->resource_name($body , $this->resource_name);
        // $re->book_details();
        // $book_tags = book_tags($responses[$key]->body());
        // $book_publishes = book_publishes($responses[$key]->body());
        
        // $this->search_book_in_nl($book_details);

        // Log::info(var_dump($book_details));
        
    }

    public function get_book_info($body)
    {
        $crawler = new Crawler($body);
        require(__DIR__ . '/../../CrawlFilters/' . $this->resource_name . '.php');
        Log::info($book_details['title']);
    }
    
    
    public function search_book_in_nl(array $book_details)
    {

        $body = Http::asForm()->post('https://opac.nlai.ir/opac-prod/search/bibliographicAdvancedSearchProcess.do' , [
            'advancedSearch.simpleSearch[0].indexFieldId' => 221091,
            'advancedSearch.simpleSearch[0].value' => $book_details['isbn'],
            'classType' => 0,
            'command' => 'I',
            'advancedSearch.simpleSearch[0].tokenized' => false
        ]);

        $this->find_book_url_in_nl_search($body);

        // $this->find_best_match_in_nl_search_result($res->body() , $book_details);

    }

    public function find_book_url_in_nl_search(string $body)
    {
        $crawler = new Crawler($body);

        $book_url_nl = $crawler->filter('#table tr a')->attr('href');

        $this->crawl_book_url_nl($book_url_nl);
    }




    // public function find_best_match_in_nl_search_result(string $body , $book_details)
    // {
        
    //     $crawler = new Crawler($body);
        
    //     $candidate_rows = [];

    //     $crawler->filter('#table tr')->each(function(Crawler $row , $i){
            
    //             $title = $this->encode_to_iso_8859_1($row->filter('#td2')->text());
    //             $writer = $this->encode_to_iso_8859_1($row->filter('#td3')->text());
    //             $year = $this->encode_to_iso_8859_1($row->filter('#td4')->text());
    //             $link = $row->filter('#td2 a')->attr('href');
            
    //             $candidate_rows[$i]['title'] = $title;    
    //             $candidate_rows[$i]['writer'] = $writer;    
    //             $candidate_rows[$i]['year'] = $year;    
    //             $candidate_rows[$i]['link'] = $link;    

    //     }); 

    //     foreach ($candidate_rows as $row) {

    //         if ( strpos($row['title'] , $book_details['title']) === false ) {
    //             continue;
    //         }
           
    //         $priority = 'B';

    //         foreach (explode(' ' , $book_details['writer']) as $value) {
    //             if ( strpos($row['writer'] , $value) !== false) {
    //                 $priority = 'A';
    //             }
    //         }

    //         $sorted_rows_according_to_priority_in_nl_search[$priority][$row['year']] = [ 
    //             'link' => $row['link'],
    //         ];

    //     }

    //     ksort($sorted_rows_according_to_priority_in_nl_search);

    //     if ($sorted_rows_according_to_priority_in_nl_search['A']) {
    //         ksort($sorted_rows_according_to_priority_in_nl_search['A']);
    //     }

    //     if ($sorted_rows_according_to_priority_in_nl_search['B']) {
    //         ksort($sorted_rows_according_to_priority_in_nl_search['B']);
    //     }

    //     $nl_url = $sorted_rows_according_to_priority_in_nl_search[0][0];

    // }


    public function crawl_book_url_nl(string $url)
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

            $this->get_creators_from_book_page_in_nl($nl_details);

            $this->send_data_to_database($nl_details , 'book_nl');
            $this->send_data_to_database(end($nl_details) , 'book_urls');

    }

    public function get_creators_from_book_page_in_nl(array $nl_details)
    {
        // get writer info

        foreach ($nl_details as $nl_row) {
            
            if ($nl_row['name'] == 'سرشناسه') {

                $comma_separated_writer_info = $nl_row['description'];

                if ( strpos($comma_separated_writer_info , '<br>') !== false ) {
                    $comma_separated_writer_info = explode( '<br>' , $comma_separated_writer_info)[0];
                    $writer_real_name = $comma_separated_writer_info[1];
                }
        
                $writer_info = explode(',' , $comma_separated_writer_info);

                $writer_name = $writer_info[1] . ' ' . $writer_info[0];
                $writer_birth = $writer_info[2];
                
            }

            if ($nl_row['name'] == 'شناسه افزوده') {

                $comma_separated_creator_info = $nl_row['description'];

                $creator_kinds = ['مترجم' , 'ویراستار' , 'تصویرگر'];

                $creator_info = explode(',' , $comma_separated_creator_info );

                if ( in_array(end($creator_info) , $creator_kinds) ) {
                    $creator_name = $creator_info[1] . ' ' . $creator_info[0];
                    $creator_birth = $creator_info[2];
                }


            }



        }

    
        // get other creators info
        




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
