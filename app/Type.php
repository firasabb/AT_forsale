<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use SoftDeletes;
    
    public function arts(){
        return $this->hasMany('\App\Art');
    }

    public function contests(){
        return $this->hasMany('\App\Contest');
    }


}
