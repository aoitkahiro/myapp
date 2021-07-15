<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('users_id');
            $table->string('users_name');
            $table->string('front'); // 科目/ユーザーごとの表面（問題文）を保存するカラム
            $table->string('back');  // 科目/ユーザーごとの裏面（答え）を保存するカラム
            $table->integer('memorized'); // 科目/ユーザーごと「未暗記(0)」「暗記済み(1)」「完璧(2)」を保存するカラム
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
        Schema::dropIfExists('histories');
    }
}
