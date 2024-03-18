<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('book_id');
            $table->string('title', 1000);
            $table->string('author', 1000);
            $table->text('description');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('added_by');
            $table->unsignedSmallInteger('total');
            $table->unsignedSmallInteger('total_active');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('book_categories');
            $table->foreign('added_by')->references('id')->on('users');
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
}
