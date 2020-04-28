<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Download extends Model
{
    

    public function asset(){

        return $this->belongsTo('\App\Asset');

    }

    public function getPath(){

        return $path = Storage::cloud()->url($this->url);

    }

    public function getMime(){

        return $mime = pathinfo($this->getPath(), PATHINFO_EXTENSION);

    }


    public function getSize(){
        return $size = Storage::cloud()->size($this->url);
    }

    public function downloadEvents(){
        return $this->hasMany('App\DownloadEvent');
    }

}
