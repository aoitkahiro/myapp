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
        Schema::create('courses', function (Blueprint $table) { // coursesというテーブルを作成
            $table->bigIncrements('id');
            $table->string('kind'); // 科目の種類（動詞、名詞、形容詞、など）を保存するカラム
            $table->string('category'); // 科目のジャンル（英単語、どうぶつ、など）を保存するカラム
            $table->string('degree'); // 科目の種類（難易度Ａ，Ｂ，Ｃなど）を保存するカラム
            $table->string('front'); // 科目の表面（問題文）を保存するカラム
            $table->string('back');  // 科目の裏面（答え）を保存するカラム
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
