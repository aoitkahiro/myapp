<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserQuizResult extends Model
{
    protected $guarded = array('id'); //'id'は通常、$guarded にする？
}
