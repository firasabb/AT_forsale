<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{

    use SoftDeletes;

    public function posts(){
        return $this->morphedByMany('App\Post', 'mediable');
    }

    public function users(){
        return $this->morphedByMany('App\User', 'mediable');
    }


    // Override the delete method to delete the actual file from S3
    public function delete(){
        Storage::delete($this->url);
        parent::delete();
    }

    /**
     * 
     * Parse The Media's Sorting Integers To Strings
     * 
     */
    public function sortingToText(){
        switch($this->sorting){
            case 0:
                return 'other';
            case 1:
                return 'featured';
            case 2:
                return 'cover';
            case 3:
                return 'user_ad';
            case 4:
                return 'avatar';
            default:
                return 'other';
        }
    }
}
