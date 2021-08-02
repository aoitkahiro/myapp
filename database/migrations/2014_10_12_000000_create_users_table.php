<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id'); // プライマリーキー
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable(); // メール認証をする際に、それをした日付を入れる部分。これを入れる決まりになっている。（Usersの中で）
            $table->string('password');
            $table->string('mygoal')->nullable();//目標を記録するところ
            $table->string('image_path')->nullable();  // 画像のパスを保存するカラム
            $table->boolean('has_learned')->default(false);//default(0) 覚えた(1)
            $table->boolean('has_known')->default(false);//default(0) 最初から知っている(1)
            $table->boolean('is_hard')->default(false);//カスタム順(0) ランダム順(1)
            $table->boolean('is_image_displayed')->default(false);//写真表示
            
             // $table->integer('course_id');が必要なのでは？
             
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
