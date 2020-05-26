<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAd extends Model
{

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function medias(){
        return $this->morphToMany('App\Media', 'mediable');
    }

    /**
     * 
     * Change status numbers to text and check if deleted or not
     * 
     */
    public function statusInText(){

        switch($this->status){

            case 0:
                return 'unapproved';

            case 1:
                return 'pending';
            
            case 2:
                return 'approved';

            default:
                return 'unknown';

        }
        return 'unknown';
    }

}
