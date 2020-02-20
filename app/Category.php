<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
    public function arts()
    {
        return $this->morphedByMany('App\Art', 'categoriable');
    }



}
