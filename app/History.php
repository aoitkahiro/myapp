<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $guarded = array('id'); // id というデータは外から変更できないようにしてくださいという意味（ほかのデータは変更できるという意味になる）
    //
}
