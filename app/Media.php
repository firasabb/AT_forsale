<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Media extends Model
{
    public function assets(){
        return $this->morphedByMany('App\Asset', 'mediable');
    }

    public function userAds(){
        return $this->morphedByMany('App\UserAd', 'mediable');
    }

    // Override the delete method to delete the actual file from S3
    public function delete(){
        Storage::cloud()->delete($this->url);
        parent::delete();
    }

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
            default:
                return 'other';
        }
    }
}
