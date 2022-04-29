<?php

namespace App\Http\Controllers\Crawler;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Crawler\ToolClassess\InsertData;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Controllers\Crawler\ToolClassess\SendHttpRequest;
use App\Http\Controllers\Crawler\ToolClassess\UpdateDatabase;

class BookDetails extends Controller
{

    public $book;
    
    public function __construct(array $book)
    {
        $this->book = $book;
    }

    
    public function crawl_new_book()
    {
        
        $send_http_request = new SendHttpRequest();
        $response = $send_http_request($this->book['url']);
        
        if ($response['status'] == '404') {
            die();
        }

        $this->get_book_info($response['body']);


        // $re = new $this->resource_name($body , $this->resource_name);
        // $re->book_details();
        // $book_tags = book_tags($responses[$key]->body());
        // $book_publishes = book_publishes($responses[$key]->body());
        
        // $this->search_book_in_nl($book_details);

        // Log::info(var_dump($book_details));
        
    }

    public function get_book_info($body)
    {

        // ************ HOUSE KEEPING

        // if there is no tag , then we need empty array to insert to database
        $book_tags = [
            'book_id' => $this->book['id'],
            'website' => $this->book['resource_kind']
        ];
        
         // if there is no publishing info , then we need empty array to insert to database
        $book_publishes = [
            'book_id' => $this->book['id'],
            'website' => $this->book['resource_kind']
        ];

        $covers_location = 'C:\Users\amir\Desktop\spa2\storage\covers\\';

        // **************

        $crawler = new Crawler($body);
        require(__DIR__ . '/../../CrawlFilters/' . $this->book['resource_name'] . '.php');
    
        // update book table because book id has already been inserted
        $book_details['book_id'] = $this->book['id'];
        $update_database = new UpdateDatabase();
        $update_database('book' , $book_details);
    
        // insert tags to book_tags table
        foreach ($book_tags as $key => $tag) {
            
            $book_tags[$key]['book_id'] = $this->book['id'];
            $book_tags[$key]['tag'] = $tag;
            $book_tags[$key]['website'] = $this->book['resource_kind'];
            $book_tags[$key]['created_at'] = now();
            $book_tags[$key]['updated_at'] = now();

        }

        $insert_data = new InsertData();
        $insert_data('book_tags' , $book_tags);

        // insert book publishes to datebase
        $insert_data('book_publishes' , $book_publishes);

        $this->search_book_in_nl($book_details['isbn']);

    }
    
    
    public function search_book_in_nl(string $isbn)
    {

        $body = Http::asForm()->post('https://opac.nlai.ir/opac-prod/search/bibliographicAdvancedSearchProcess.do' , [
            'advancedSearch.simpleSearch[0].indexFieldId' => 221091,
            'advancedSearch.simpleSearch[0].value' => $isbn,
            'advancedSearch.simpleSearch[0].indexFieldId' => 10070,
            'advancedSearch.simpleSearch[0].value' => $this->book['resource_persian_name'],
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

                $nl_details[$i]['book_id'] = $this->book['id'];

                if ($node->attr('width') == "20%") {
                    $name = $this->encode_to_iso_8859_1($node->text());
                    $nl_details[$i]['name'] = $name;
                }

                if ($node->attr('width') == "75%") {
                    $description = $this->encode_to_iso_8859_1($node->text());
                    $nl_details[$i]['description'] = $description;
                }
               
            });

            
            $insert_data = new InsertData();
            $insert_data('book_nl' , $nl_details);
            
            // insert nl main address to database
            $nl_url = [
                'book_id' => $this->book['id'],
                'url_name' => 'nl',
                'url' => $crawler->filter('.formcontent')->eq(1)->filter('input')->attr('value'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $insert_data('book_urls' , $nl_url);
            
            $this->get_creators_from_book_page_in_nl($nl_details);

    }

    // public function get_creators_from_book_page_in_nl(array $nl_details)
    // {
    //     // get writer info

    //     foreach ($nl_details as $nl_row) {
            
    //         if ($nl_row['name'] == 'سرشناسه') {

    //             $comma_separated_writer_info = $nl_row['description'];

    //             if ( strpos($comma_separated_writer_info , '<br>') !== false ) {
    //                 $comma_separated_writer_info = explode( '<br>' , $comma_separated_writer_info)[0];
    //                 $writer_real_name = $comma_separated_writer_info[1];
    //             }
        
    //             $writer_info = explode(',' , $comma_separated_writer_info);

    //             $writer_name = $writer_info[1] . ' ' . $writer_info[0];
    //             $writer_birth = $writer_info[2];
                
    //         }

    //         if ($nl_row['name'] == 'شناسه افزوده') {

    //             $comma_separated_creator_info = $nl_row['description'];

    //             $creator_kinds = ['مترجم' , 'ویراستار' , 'تصویرگر'];

    //             $creator_info = explode(',' , $comma_separated_creator_info );

    //             if ( in_array(end($creator_info) , $creator_kinds) ) {
    //                 $creator_name = $creator_info[1] . ' ' . $creator_info[0];
    //                 $creator_birth = $creator_info[2];
    //             }

    //         }

    //     }
    
    //     // get other creators info

    // }


    public function encode_to_iso_8859_1(string $item)
    {
        return iconv("UTF-8" , "ISO-8859-1" , $item);
    }

}
