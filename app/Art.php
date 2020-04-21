<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Art extends Model
{
    
    use SoftDeletes;

    public function approvedArts(){
        return $this->where('status', 2);
    }

    public function downloads(){
        return $this->hasMany('\App\Download', 'art_id');
    }

    public function user(){
        return $this->belongsTo('\App\User');
    }

    public function tags()
    {
        return $this->morphToMany('\App\Tag', 'taggable');
    }

    public function category(){
        return $this->belongsTo('\App\Category');
    }

    public function comments()
    {
        return $this->hasMany('\App\Comment', 'art_id');
    }

    public function reports()
    {
        return $this->morphMany('\App\Report', 'reportable');
    }

    public function medias(){
        return $this->morphToMany('App\Media', 'mediable');
    }

    public function cover(){
        return $this->medias->where('sorting', 'cover')->first();
    }

    public function featured(){

        $check_if_exists = $this->medias->where('sorting', 'featured')->first();
        if(empty($check_if_exists)){
            return 'featured/default/default.jpg';
        }
        return $check_if_exists->url;
    }

    /**
     * 
     * Change status numbers to text and check if deleted or not
     * 
     */
    public function statusToText(){

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

            default:
                return 'unknown';

        }
        return 'unknown';
    }


}
