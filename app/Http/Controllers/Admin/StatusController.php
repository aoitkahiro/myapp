<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\History;

class StatusController extends Controller
{
   public function has_learned()
   {     
      return view('admin.course.index');
   }

   public function store(Request $request)
   {
      //インスタンス作成
      $history = new History;
       
      //Inputタグのusers_id属性がusers_idの場合 $request->users_id で値を受け取る
      //モデルインスタンスのusers_id属性に代入
      $history->users_id = Auth::id();
       
      //saveメソッドが呼ばれると新しいレコードがデータベースに挿入される
      $history->save();
   }
}