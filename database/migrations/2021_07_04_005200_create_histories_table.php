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
            $table->integer('user_id'); //名づけを単数形にすることで、users tableの $table->bigIncrements('id');を参照する（Laravelの機能）
            $table->string('front'); // 科目/ユーザーごとの表面（問題文）を保存するカラム 
            $table->string('back');  // 科目/ユーザーごとの裏面（答え）を保存するカラム
            $table->integer('hide_learned'); // 科目/ユーザーごと「覚えたけど出す(0)」「覚えたから消す(1)]を保存するカラム // booleanの方が良い？
            $table->integer('hide_known'); // 科目/ユーザーごと「最初から知ってるけど出す(0)」「最初から知ってるので消す(1)」を保存するカラム // booleanの方が良い？
            $table->timestamps();
            //$table->string('user_name'); //7.26 なくても大丈夫という指摘を受け、、変更の手間を減らすために削除
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
