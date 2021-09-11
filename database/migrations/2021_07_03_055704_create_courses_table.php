<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('kind')->nullable(); // 単語の種類（動詞、名詞、形容詞、など）を保存するカラム（正規化のためintegerで保存？）
            $table->string('category')->nullable(); // 単語のジャンル（英単語、どうぶつ、など）を保存するカラム
            $table->string('difficulty')->nullable(); // 単語の難易度（難易度Ａ，Ｂ，Ｃなど）を保存するカラム（degree → difficultyにリネーム）
            $table->string('front'); // 単語の表面（問題文）を保存するカラム
            $table->string('back');  // 単語の裏面（答え）を保存するカラム
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
        Schema::dropIfExists('courses');
    }
}
