<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = ['id'];//idに関しては、連想配列で代入できない値にしますという宣言 fillableの逆
  //protected $guarded = array('id'); でも同じ意味

    //updateで連想配列を渡すためのコード（Controller内）
    public function next()
    {
        return $this->where('id', '>', $this->id)->orderBy('id', 'asc')->first();
    } 

}
