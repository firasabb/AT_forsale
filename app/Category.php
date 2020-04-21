<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
    public function arts(){
        return $this->hasMany('\App\Art');
    }

    public function approvedArts(){
        return $this->hasMany('\App\Art')->where('status', 2);
    }

    public function contests(){
        return $this->hasMany('\App\Contest');
    }

    public function medias(){
        return $this->morphToMany('App\Media', 'mediable');
    }

    public function tags(){
        return $this->morphToMany('\App\Tag', 'taggable');
    }

    public function option(){
        return $this->morphOne('\App\Option', 'optionable');
    }

    public function backgroundColor(){
        $option = $this->option()->where('name', 'background_color')->first();
        if(!empty($option)){
            return $option->value;
        }
        return '#F34444';
    }

}
