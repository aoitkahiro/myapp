<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    protected $guarded = ['id'];//idに関しては、連想配列で代入できない値にしますという宣言 fillableの逆
  //protected $guarded = array('id'); でも同じ意味
  
  
    public static function deleteCategory($unique_category)
    {
      $collection_for_delete = Course::where('category', $unique_category)->get();
      // dd($collection_for_delete,$array_for_delete[0]->id);
      $id_array =[];
      foreach($collection_for_delete as $instance){
        // dd($instance,$instance->id);
       array_push($id_array,$instance->id);
      }
      
      // dd($id_array, UserQuizResult::whereIn('course_id', $id_array)->get());
      Course::where('category', $unique_category)->delete();
      UserQuizResult::whereIn('course_id', $id_array)->delete();
    }

    //updateで連想配列を渡すためのコード（Controller内）
    public function next()
    {
        return $this->where('id', '>', $this->id)->orderBy('id', 'asc')->first();
    }
    public function getImageFileName(){
      $path = 'public/tango/';
    
      $jpgFileName = $this->id. '.jpg';//このインスタンスメソッドを呼び出した、メソッドを指す。
      //呼び出す単語によって、$thisの中身は変わる。インスタンスメソッドのポイント。
      $pngFileName = $this->id. '.png';
      $defaultFileName = 'noimage.jpg';
      $issetMessage = 'ヒント画像あり';
    
      if(Storage::exists($path . $jpgFileName)){
        return $jpgFileName;
      } elseif(Storage::exists($path . $pngFileName)){
        return $pngFileName;
      } else {
        return $defaultFileName;
      }
    }
    
}
