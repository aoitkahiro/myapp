<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
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
           'email' => 'tester@hoge.com',
           'password' => Hash::make('abcd1234'),
           'mygoal' => "TOEIC score 750",
        ]);    
    }
}
