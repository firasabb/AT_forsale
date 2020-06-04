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


    public function assets()
    {
        return $this->morphedByMany('App\Asset', 'taggable');
    }


    public function categories(){
        return $this->morphedByMany('App\Category', 'taggable');
    }


    public function approvedAssets(){
        return $this->assets()->where('status', 2);
    }
}
