<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookIssueLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_issue_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('book_student_id');
            $table->unsignedBigInteger('issue_by');
            $table->timestamp('return_time')->nullable();
            $table->timestamps();
            $table->foreign('book_student_id')->references('id')->on('book_student');
            $table->foreign('issue_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_issue_logs');
    }
}
