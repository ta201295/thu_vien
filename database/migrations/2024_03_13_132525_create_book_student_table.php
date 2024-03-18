<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_student', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedInteger('number')->comment('number of borrowed books');
            $table->tinyInteger('status')->default(0)->comment('0:pending, 1:approved, 2:rejected, 3:borrowed, 4:completed');
            $table->date('expired_time')->nullable();
            $table->timestamps();
            $table->foreign('book_id')->references('book_id')->on('books');
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_student');
    }
}
