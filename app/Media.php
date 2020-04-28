<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    public function assets(){

        return $this->morphedByMany('App\Asset', 'mediable');

    }
}
