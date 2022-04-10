<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerController extends Controller
{

    public $resource;
    public $new_books;
    
    /**
     * extract resource urls that need to be crawled
     *
     * @return array
     */
    public function extract_resource_urls()
    {
        // TODO change $website after main crawl is done
        // TODO it has to get recource urls based on last crawled time
        // ! this fuctios is getting just one resource URL every minute

        $website = DB::table('book_resource_urls')
        ->orderByDesc('created_at')
        ->first();

        $url = str_replace(['{var1}' , '{var2}'] , [$website->var1 , $website->var2] , $website->url);
        
        $this->resource['url'] = $url;
        $this->resource['website'] = $website->website;
        $this->resource['filter'] = $website->filter;
        
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
        $this->resource['body'] = $this->send_http_request($this->resource['url']);
        $this->extract_new_books();

    }
    
    /**
     * extract list of new books that need to be crawled form reosurce page
     *
     * @param  string $body , $filter
     * @return array $new_books
     */
    public function extract_new_books()
    {
        $crawler = new Crawler($this->resource['body']);
        $new_books_urls = $crawler->filter($this->resource['filter'])->extract(['href']);

        foreach ($new_books_urls as $key => $url) {
            $this->new_books[$key]['book_id'] = $this->get_book_id();
            $this->new_books[$key]['website'] = $this->resource['website'] ;
            $this->new_books[$key]['url'] = $url ;
        } 




        $this->send_data_to_database($this->new_books , 'book_urls');

    }


    public function remove_existing_boos()
    {
        # code...
    }
  
        
    /**
     * crawl book page
     *
     * @param string $url
     * @return void
     */
    public function crawl_book_url(string $url)
    {

    }

    
    /**
     * extract book details from book page 
     * e.g. title - isbn - pages etc
     *
     * @param  mixed $body
     * @return array $book_details
     */
    public function extract_book_details_from_book_page(string $body)
    {
        # code...
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
