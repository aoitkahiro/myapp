<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersQuizResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_quiz_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id'); 
            $table->integer('course_id'); 
            $table->integer('challenge_id'); 
            $table->boolean('result'); 
            $table->timestamps();
            $table->unique(['user_id','course_id','challenge_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_quiz_results');
    }
}
