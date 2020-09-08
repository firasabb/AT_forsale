<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{

    use SoftDeletes;


    public function posts()
    {
        return $this->morphedByMany('App\Post', 'taggable');
    }


    public function categories(){
        return $this->morphedByMany('App\Category', 'taggable');
    }


    public function approvedPosts(){
        return $this->posts()->where('status', 2);
    }
}
