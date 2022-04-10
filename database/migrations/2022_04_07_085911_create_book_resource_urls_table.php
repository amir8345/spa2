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
        Schema::create('book_resource_urls', function (Blueprint $table) {
            $table->id();
            $table->string('website');
            $table->string('url');
            $table->string('var1')->nullable();
            $table->string('var2')->nullable();
            $table->string('crawler_filter')->nullable();
            $table->timestamp('last_crawled_at')->default(NULL);
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
        Schema::dropIfExists('book_resource_urls');
    }
};
