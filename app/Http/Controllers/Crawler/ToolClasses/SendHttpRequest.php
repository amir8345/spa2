<?php

namespace App\Http\Controllers\Crawler\ToolClassess;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class SendHttpRequest extends Controller
{

     /**
    * send http request and return response body
    *
    * @param  string $url
    * @return string $body
    */

    public function __invoke(string $url)
    {
        $res = Http::get($url);

        return [ 'body' => $res->body() , 'status' => $res->status()];
    }


}