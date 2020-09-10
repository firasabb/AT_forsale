<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    // Add The background_color Attribute
    protected $appends = ['background_color'];
    
    public function posts(){
        return $this->hasMany('\App\Post');
    }

    public function approvedPosts(){
        return $this->hasMany('\App\Post')->where('status', 2);
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

    /**
     * 
     * Get the background color option of this category
     * 
     */
    public function backgroundColor(){
        $option = $this->option()->where('name', 'background_color')->first();
        if(!empty($option)){
            return $option->value;
        }
        return '#F34444';
    }

    /**
     * 
     * Check If The Category is a Child of Another Category
     * 
     */
    public function isChild(){
        if($this->parent_id){
            return true;
        }
        return;
    }

    /**
     * 
     * Check If The Category is a Parent of Another Category
     * 
     */
    public function isParent(){
        if(!$this->parent_id){
            return true;
        }
        return;
    }

    /**
     * 
     * Get All Parent Categories
     * 
     */
    public function parentCategories(){
        $categories = Category::whereNull('parent_id')->get();
        return $categories;
    }

    /**
     * 
     * Get The Category Background Color (Attribute)
     * 
     */
    public function getBackgroundColorAttribute(){
        return $this->backgroundColor();
    }


    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function collectionWithBackground(array $models = [])
    {
        return new CustomCollection($models);
    }

}
