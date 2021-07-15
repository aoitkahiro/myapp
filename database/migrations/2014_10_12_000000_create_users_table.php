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
            $table->integer('status');//default(0) 覚えた(1)
            $table->integer('status2');//default(0) 最初から知っている(1)
            $table->integer('visible');//default(0) 表示する(1) 非表示にする(2)
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
