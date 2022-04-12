<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ResourceTag;
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

                DB::table('book_urls')->insert([
                    'book_id' => $book_id,
                    'url_name' => $this->resource_tag->resource->name,
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
            $this->crawl_new_books();
            
        }
        
        
        
        public function update_resource_tag(array $update_array)
        {
            DB::table('resource_tags')
            ->where('id' , $this->resource_tag['id'])
            ->update($update_array);
        }

            
            /**
            * crawl book page
            *
            * @param string $url
            * @return void
            */
            public function crawl_new_books()
            {

                foreach ($this->new_books as $key => $new_book) {
                    
                    $body = $this->send_http_request($new_book['url']);
                    $crawler = new Crawler($body);

                    $filters = DB::table('resource_book_crawl_filters')
                    ->where('resource_id' , $this->resource_tag['resource_id'])
                    ->first();

                    $details = [ 'title', 'title2', 'lang', 'age', 'city', 'isbn', 
                    'format', 'size', 'weight', 'cover', 'paper', 'pages', 'colorful', 
                    'binding', 'about', ];


                    foreach ($details as $detail)    {
                        if ( !empty($filters->$detail) ) {
                            $book_details[$detail] = $crawler->filter($filters->$detail)->text('');
                        }
                    }


                    dd($book_details);

                    $book_publishes = [
                        'nobat' => $crawler->filter($filters->nobat)->text(''),
                        'year' => $crawler->filter($filters->year)->text(''),
                        'month' => $crawler->filter($filters->month)->text(''),
                        'date' => $crawler->filter($filters->date)->text(''),
                        'number' => $crawler->filter($filters->number)->text(''),
                    ];

                    $book_original_title = [
                        'title' => $crawler->filter($filters->original)->text('')
                    ];
                    
                    $book_urls = [
                        [
                            'url_name' => 'amazon' , 
                            'url' => $crawler->filter($filters->url_amazon)->link()->getUri(),
                        ],
                        [
                            'url_name' => 'fidibo' , 
                            'url' => $crawler->filter($filters->url_fidibo)->link()->getUri(),
                        ],
                        [
                            'url_name' => 'content' , 
                            'url' => $crawler->filter($filters->url_content)->link()->getUri(),
                        ],
                        [
                            'url_name' => 'preface' , 
                            'url' => $crawler->filter($filters->url_preface)->link()->getUri(),
                        ],
                        
                    ];

                    foreach ($crawler->filter($filters->tag) as $tag) {
                        $book_tags[] = [
                            'book_id' => $new_book['id'],
                            'tag' => $tag->nodeValue,
                            'website' => $this->resource_tag['name']
                        ];
                    }


                }

            }
            
            
            
            /**
            * extract book urls from book page like 
            * e.g. sample link - content link - fidibo link etc
            *
            * @param  mixed $body
            * @return array $book_urls
            */
            public function extract_book_urls_from_book_page(string $body)
            {
                # code...
            }
            
            
            /**
            * extract book tags from book page
            * 
            * e.g. ادبیات - رمان etc 
            * @param  mixed $body
            * @return array $tags
            */
            public function extract_book_tags_from_book_page(string $body)
            {
                # code...
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
            
            
            
            
            
            
            
            /*
            
            public $books_url;
            
            public function add_new_book_urls()
            {
                $website = DB::table('book_resource_urls')
                ->orderByDesc('created_at')
                ->first();
                
                $url = $website->url . strval($website->num);       
                
                $body = $this->send_http_request($url);
                
                $crawler = new Crawler($body);
                
                $this->books_url = $crawler->filter('.prgridwrap')->extract(['href']);
                
                // insert to database
                
            }
            
            
            public function crawl_new_book_urls()
            {
                
            }
            
            public function send_http_request($url)
            {
                $res = Http::get($url);
                
                return $res->body();
            }
            
            foreach ($book_urls as $book_url) {
                
                $book_id = Book::insertGetId(['title' => '']);
                $book_details['book_id'] = $book_id;
                
                $body = $this->send_http_request($book_url);
                
                $crawler = new Crawler($body);
                
                // book deails 
                
                $book_details['title'] = $crawler->filter('.product_title')->text('');
                $book_details['title2'] = $crawler->filter('.summary > h4')->text('');
                
                $array = $crawler->filter('.bookspcell')->extract(['_text']);
                
                $array = array_map('trim', $array);
                
                foreach ($array as $key => $value) {
                    
                    if ($value == 'شابک') {
                        $book_details['isbn'] = $array[$key + 1];
                    }
                    if ($value == 'تعداد صفحات') {
                        $book_details['pages'] = $array[$key + 1];
                    }
                    if ($value == 'قطع') {
                        $book_details['format'] = $array[$key + 1];
                    }
                    if ($value == 'جلد') {
                        $book_details['cover'] = $array[$key + 1];
                    }
                    if ($value == 'وزن') {
                        $book_details['weight'] = $array[$key + 1];
                    }
                    
                }
                
                $about = $crawler->filter('#tab-description > p')->html('');
                
                if (empty($about)) {
                    $about = $crawler->filter('#tab-description')->html('');
                }
                
                if (empty($about)) {
                    $about = '';
                }
                
                $book_details['about'] = $about;
                
                // book urls detials
                
                $crawler->filter('.arrowitem')->each(function($node , $i) {
                    if ($node->text('') == 'نسخه الکترونیک') {
                        $book_urls_details['url_name'] = 'fidibo';
                        $book_urls_details['url'] = $node->attr('href');
                    }
                    if ($node->text('') == 'صفحه کتاب در آمازون') {
                        $book_urls_details['url_name'] = 'amazon';
                        $book_urls_details['url'] = $node->attr('href');
                    }
                    
                });
                
                
                
                // book tags details
                
                $crawler->filter('.woocommerce-breadcrumb > a')->each(function($node , $i) use ($book_id){
                    if ($i != 0 && $i != 1) {
                        $book_tags_details[] = [
                            'book_id' => $book_id , 
                            'website' => 'publisher' , 
                            'tag' => $node->text()
                        ];
                    }
                    var_dump($book_tags_details);
                });
                
                
            } 
            
            
            
            */
            
        }
        