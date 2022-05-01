<?php

use App\Models\Book;
use App\Models\Shelf;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_shelf', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Book::class);
            $table->foreignIdFor(Shelf::class);
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
        Schema::dropIfExists('book_shelf');
    }
};
