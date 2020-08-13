<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Download extends Model
{
    
    use SoftDeletes;

    public function post(){

        return $this->belongsTo('\App\Post');

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


    // Override the delete method to delete the actual file from S3
    public function delete(){
        Storage::cloud()->delete($this->url);
        parent::delete();
    }
}
