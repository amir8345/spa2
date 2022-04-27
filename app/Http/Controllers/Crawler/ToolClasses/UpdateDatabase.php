<?php

namespace App\Http\Controllers\Crawler\ToolClassess;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UpdateDatabase extends Controller
{
    /**
    * @param  string $table
    * @return array $array
    */

    public function __invoke(string $table , array $array)
    {
        DB::table($table)->update($array);
    }
}
