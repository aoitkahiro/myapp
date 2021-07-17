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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable(); // メール認証をする際に、それをした日付を入れる部分。これを入れる決まりになっている。（Usersの中で）
            $table->string('password');
            $table->integer('learned_btn')->default(0);//default(0) 覚えた(1)
            $table->integer('known_btn')->default(0);//default(0) 最初から知っている(1)
            $table->integer('order')->default(0);//カスタム順(0) ランダム順(1)
            $table->integer('show_pictures')->default(0);//最初は非表示(0) 表示にする(1)
            $table->string('mygoal');//目標を記録するところ
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
