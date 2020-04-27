<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DownloadEvent extends Model
{
    public function download(){
        return $this->belongsTo('\App\Download');
    }

    public function user(){
        return $this->belongsTo('\App\User');
    }
}
