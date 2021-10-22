<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
           'name' => 'tester',
           'email' => 'tt@t.jp',
           'password' => Hash::make('abcd1234'),
           'mygoal' => "TOEIC score 750",
        ],[
           'name' => 'testuser',
           'email' => 'tt@t.jp',
           'password' => Hash::make('password'),
           'mygoal' => "",
        ]);    
    }
}
