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
        Schema::create('book_publishes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id');
            $table->string('nobat')->nullable();
            $table->string('year')->nullable();
            $table->string('month')->nullable();
            $table->string('date')->nullable();
            $table->string('number')->nullable();
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
        Schema::dropIfExists('book_publishes');
    }
};
