<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Download extends Model
{
    

    public function art(){

        return $this->belongsTo('\App\Art');

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

}
