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
      $category = urldecode($request->category);
      // dd($category);
      //databaseを検索してレコードがある場合、ない場合で実行コードを分けるよう実装する。
      $history = History::where('user_id',Auth::id())->where('course_id',$request->course_id)->first();
      //レコードを探すコード
      //->get();だとインスタンスの「配列」が返ってきてしまうのでエラーになる
      //historiesテーブルを検索して、user_id , couse_idのカラム２つで検索している（whereは複数件のインスタンスを返すが、この場合firstだけ返してくる）
      // dd($history,$request->all());
      if($history != NULL){
         $history->update(['learning_level'=>$request->learning_level]);
         $url = 'admin/course/wordbook?tango_id=' . $request->tango_id . '&category=' . $request->category;
         return redirect($url);
      }else{
         //インスタンス作成
         $history = new History;
         
         // dd($history);
         $form = $request->all();
         //Inputタグのusers_id属性がusers_idの場合 $request->users_id で値を受け取る
         //モデルインスタンスのusers_id属性に代入
         $history->user_id = Auth::id(); //use Auth; と書かないと使えない！
         
         unset($form['_token']);
         unset($form['tango_id']);
         unset($form['category']);
         //Historyモデルのインスタンスである$historyに、$formの中にあるデータを詰め込む
         $history->fill($form);
         //saveメソッドが呼ばれると新しいレコードがデータベースに挿入される
         $history->save();
         $url = 'admin/course/wordbook?tango_id=' . $request->tango_id . '&category=' . $request->category;
         return redirect($url);
      }
   }
}