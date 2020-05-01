<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewEvent extends Model
{
    public function asset(){
        return $this->belongsTo('\App\Asset');
    }

    public function user(){
        return $this->belongsTo('\App\User');
    }
}
