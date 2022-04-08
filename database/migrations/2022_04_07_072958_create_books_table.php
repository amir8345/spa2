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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('title2')->nullable();
            $table->string('lang')->nullable();
            $table->string('city')->nullable();
            $table->string('age')->nullable();
            $table->string('isbn')->nullable();
            $table->string('format')->nullable();
            $table->string('size')->nullable();
            $table->string('weight')->nullable();
            $table->string('cover')->nullable();
            $table->string('paper')->nullable();
            $table->string('pages')->nullable();
            $table->string('colorful')->nullable();
            $table->string('binding')->nullable();
            $table->text('about')->nullable();
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
        Schema::dropIfExists('books');
    }
};
