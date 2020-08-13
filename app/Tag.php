<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model implements Searchable
{

    use SoftDeletes;


    public function getSearchResult(): SearchResult
     {
     
         return new \Spatie\Searchable\SearchResult(
            $this,
            $this->name
         );
     }


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
