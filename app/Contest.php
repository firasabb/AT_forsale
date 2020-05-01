<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contest extends Model
{

    use SoftDeletes;

    public function user(){

        return $this->belongsTo('\App\User');

    }


    public function category(){

        return $this->belongsTo('\App\Category');

    }


    public function prizes(){

        return $this->hasMany('\App\Prize');

    }


    /**
     * 
     * Change status numbers to text and check if deleted or not
     * 
     */
    public function statusInText(){

        if($this->trashed()){
            return 'deleted';
        }

        switch($this->status){

            case 0:
                return 'unapproved';

            case 1:
                return 'pending';
            
            case 2:
                return 'approved';

            case 3:
                return 'done';

            default:
                return 'unknown';

        }
        return 'unknown';
    }


}
