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
            $table->integer('book_student_id')->unsigned();
            $table->integer('issue_by')->unsigned();
            $table->timestamp('return_time')->nullable();
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
        Schema::dropIfExists('book_issue_logs');
    }
}
