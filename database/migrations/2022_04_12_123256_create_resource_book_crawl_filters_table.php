<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_book_crawl_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id');
            $table->string('title');
            $table->string('title2');
            $table->string('lang');
            $table->string('city');
            $table->string('age');
            $table->string('isbn');
            $table->string('format');
            $table->string('size');
            $table->string('weight');
            $table->string('cover');
            $table->string('paper');
            $table->string('pages');
            $table->string('colorful');
            $table->string('binding');
            $table->string('about');
            $table->string('nobat');
            $table->string('year');
            $table->string('month');
            $table->string('date');
            $table->string('number');
            $table->string('tag');
            $table->string('url_amazon');
            $table->string('url_fidibo');
            $table->string('url_content');
            $table->string('url_preface');
            $table->string('original_title');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_book_crawl_filters');
    }
};
