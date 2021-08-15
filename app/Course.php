<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
 
    public function next()
    {
        return $this->where('id', '>', $this->id)->orderBy('id', 'asc')->first();
    } 

}
