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
        
        
       

        
        
    }
    