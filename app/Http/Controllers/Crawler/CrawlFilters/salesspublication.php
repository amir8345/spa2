<?php


// TITLE
$book_details['title'] = $crawler->filter('h1')->text('');


// PAGES - COVER _ FORMAT
$array = $crawler->filter('#tab-description td')->extract(['_text']);

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
$about_pragraphs = $crawler->filter('#tab-description > p')->reduce(function($node){
    
    if ( strlen($node->text()) < 3 ) {
        return false;
    }
    if ( strpos($node->html() , '<strong>') === 0) {
        return false;
    }
    
});

$about = '';

foreach ($about_pragraphs as $about_pragraph) {
    $about .= '<p>' . $about_pragraph->nodeValue . '</p>';
}

$book_details['about'] = $about;


// COVER IMAGE

  // if book covers url is relative then we need baseURI
  $parsed_url = parse_url($book['url']);
  $baseURI = $parsed_url['scheme'] . '://' . $parsed_url['host'];

$cover_image_src = $this->baseURI . '/' . 
$crawler->filter('.thumbnails img')->attr('src');

$cover_address = $covers_location . trim($book_details['isbn']) . '.jpg';

$cover_url = file_get_contents(str_replace(' ' , '%20' , $cover_image_src) );

file_put_contents( $cover_address , $cover_url );


// BOOK TAGS
$tags = $crawler->filter('.breadcrumb li')->extract(['_text']);

unset($tags[0]);
unset($tags[count($tags)]);



// BOOK PUBLISHES
$array = $crawler->filter('#tab-description td')->extract(['_text']);

foreach ($array as $key => $value) {
    
    if ( strpos($value , 'نوبت' ) !== false ) {
        $book_publishes['nobat'] = $array[$key + 1];
    }

}  




?>