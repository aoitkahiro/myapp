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
      $history = History::where('user_id',Auth::id())->where('course_id',$request->course_id)->first();
      if($history != NULL){
         $history->update(['learning_level'=>$request->learning_level]);
         $url = 'admin/course/wordbook?tango_id=' . $request->tango_id . '&category=' . $request->category;
         return redirect($url);
      }else{
         $history = new History;
         $form = $request->all();
         $history->user_id = Auth::id();
         
         unset($form['_token']);
         unset($form['tango_id']);
         unset($form['category']);
         $history->fill($form);
         $history->save();
         $url = 'admin/course/wordbook?tango_id=' . $request->tango_id . '&category=' . $request->category;
         return redirect($url);
      }
   }
   
   public function levelChange(Request $request)
   {
      $user = Auth::user();
      $user->update(['looking_level'=>$request->looking_level]);
      $url = 'admin/course/wordbook?tango_id=' . $request->tango_id . '&category=' . $request->category;
      return redirect($url);
   }
   public function changeIsImageDisplayed(Request $request)
   {
      $user = Auth::user();
      if($request->is_image_displayed =="true"){
         $bool = true;
      }else{
         $bool = false;
      }
      $user->is_image_displayed = $bool;
      $user->save();
      $url = 'admin/course/profile';
      return redirect($url);
   }
}