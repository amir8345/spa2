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
        Schema::create('resource_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id');
            $table->string('tag');
            $table->string('num');
            $table->string('filter');
            $table->timestamp('last_crawled_at');
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
        Schema::dropIfExists('resource_tags');
    }
};
