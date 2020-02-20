<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    public function arts(){

        return $this->morphedByMany('App\Art', 'taggable');

    }
}
