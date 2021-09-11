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
            $table->string('mygoal')->nullable();//目標を記録するカラム
            $table->string('image_path')->nullable();
            $table->integer('looking_level')->default(0);//全部表示(0) 「最初から知っている」語だけ消す(1) 「覚えた」も消す(2)
            $table->boolean('word_ordering')->default(TRUE);//カスタム順(TRUE) ランダム順(FALSE)
            $table->boolean('is_image_displayed')->default(FALSE);//ヒント画像を最初から表示する(TRUE),しない(false)
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
