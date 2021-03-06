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
            $table->integer('course_id'); // courses_tableのプライマリーキーidと一致する。「どの単語がhide_learned,hide_known なのか」を管理する
            $table->integer('learning_level')->default(0); // user_id-course-idごとに「default(0)」「覚えた(1)「最初から知ってる(2)」
            $table->timestamps();
            //$table->string('user_name'); //7.26 なくても大丈夫という指摘を受け、、変更の手間を減らすために削除
            $table->unique(['user_id', 'course_id']); //二つ合わせてunique、という書き方
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
