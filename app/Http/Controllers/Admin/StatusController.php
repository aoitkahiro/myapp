<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\History;
use Auth;

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
      
      $form = $request->all();
       
      //Inputタグのusers_id属性がusers_idの場合 $request->users_id で値を受け取る
      //モデルインスタンスのusers_id属性に代入
      $history->user_id = Auth::id(); //use Auth; と書かないと使えない！
      
      unset($form['_token']);
      
      //Historyモデルのインスタンスである$historyに、$formの中にあるデータを詰め込む
      $history->fill($form);
      //saveメソッドが呼ばれると新しいレコードがデータベースに挿入される
      $history->save();
      
      //return view('admin.course.wordbook');
      //return redirect()->action('Admin\CourseController@wordbook');
      return redirect('admin/course/wordbook?tango_id=' . $request->course_id);
   }
}