<?php


use Symfony\Component\DomCrawler\Crawler;


function book_details(string $body)
{
    $crawler = new Crawler($body);

    // TITLE
    $book_details['title'] = $crawler->filter('h1')->text('');



    // PAGES - COVER _ FORMAT
    $array = $crawler->filter('tbody tr td')->extract(['_text']);
    
    foreach ($array as $key => $value) {

        if ( strpos($value , 'صفحه') !== false ) {
            $book_details['pages'] = $array[$key + 1];
        }
        if ( strpos($value , 'قطع') !== false ) {
            $book_details['format'] = $array[$key + 1];
        }
        if ( strpos($value , 'جلد') !== false ) {
            $book_details['cover'] = $array[$key + 1];
        }

    }  


    // ISBN
    foreach ($crawler->filter('.product-li') as $value) {

        if ( strpos($value->nodeValue , '978') !== false ) {
            $book_details['isbn'] = str_replace('شابک:' , '' , $value->nodeValue);
        }
        if ( strpos($value->nodeValue , 'نویسنده:') !== false ) {
           $writer =  str_replace('نویسنده: ' , '' , $value->nodeValue);
        }

    }



    // ABOUT
    $r = $crawler->filter('#tab-description > p')->reduce(function($node , $i){
        
        if ( strlen($node->text()) < 3 ) {
            return false;
        }
        if ( strpos($node->html() , '<strong>') === 0) {
            return false;
        }
       
    });

    $about = '';

    foreach ($r as $value) {
        $about .= '<p>' . $value->nodeValue . '</p>';
    }

    $book_details['about'] = $about;

    return $book_details;

}


?>