<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    protected $guarded = ['id'];
  
  
    public static function deleteCategory($unique_category)
    {
      $collection_for_delete = Course::where('category', $unique_category)->get();
      $id_array =[];
      foreach($collection_for_delete as $instance){
       array_push($id_array,$instance->id);
      }
      
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
    
      $jpgFileName = $this->id. '.jpg';
      //呼び出す単語によって、$thisの中身は変わる
      $pngFileName = $this->id. '.png';
      $issetMessage = 'ヒント画像あり';
    
      if(Storage::exists($path . $jpgFileName)){
        return $jpgFileName;
      } elseif(Storage::exists($path . $pngFileName)){
        return $pngFileName;
      } else {
        return false; //Viewでnoimageの画像を返す
      }
    }
    
}
