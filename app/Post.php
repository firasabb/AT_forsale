<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Post extends Model
{
    
    use SoftDeletes;

    public function approvedPosts(){
        return $this->where('status', 2);
    }

    public function downloads(){
        return $this->hasMany('\App\Download', 'post_id');
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
        return $this->hasMany('\App\Comment', 'post_id');
    }

    public function reports()
    {
        return $this->morphMany('\App\Report', 'reportable');
    }

    public function medias(){
        return $this->morphToMany('App\Media', 'mediable');
    }
    
    public function downloadEvents(){
        return $this->hasMany('App\DownloadEvent');
    }

    public function viewEvents(){
        return $this->hasMany('App\ViewEvent');
    }

    public function licenses(){
        return $this->belongsToMany('\App\License');
    }

    /**
     * 
     * Only published posts
     * 
     */
    public function publishedPosts(){
        return $this->where('status', 2);
    }

    /**
     * 
     * Get the post's cover url
     * @return string
     * 
     */
    public function cover(){
        $check_if_exists = $this->medias->where('sorting', 2)->first();
        if(empty($check_if_exists)){
            return 'featured/default/default.jpg';
        }
        return $check_if_exists->url;
    }

    /**
     * 
     * Get the post's cover object
     * @return Media
     * 
     */
    public function originalCover(){
        return $this->medias->where('sorting', 2)->first();
    }

    /**
     * 
     * Count the post's download events (DISTINCT user_id and ip_address)
     * @return int
     * 
     */
    public function downloadEventsCount(){
        $count = $this->downloadEvents()->whereNull('user_id')->distinct('ip_address')->count();
        $count += $this->downloadEvents()->whereNotNull('user_id')->distinct('user_id')->count();
        return $count;
    }

    /**
     * 
     * Count the post's view events (DISTINCT user_id and ip_address)
     * @return int
     * 
     */
    public function viewEventsCount(){
        $count = $this->viewEvents()->whereNull('user_id')->distinct('ip_address')->count();
        $count += $this->viewEvents()->whereNotNull('user_id')->distinct('user_id')->count();
        return $count;
    }


    /**
     * 
     * Get the featured media file's url of the post
     * @return string
     * 
     */
    public function featured(){

        $check_if_exists = $this->medias->where('sorting', 1)->first();
        if(empty($check_if_exists)){
            return 'featured/default/default.jpg';
        }
        return $check_if_exists->url;
    }
    
    /**
     * 
     * Get the featured media file's object of the post
     * @return Media
     * 
     */
    public function originalFeatured(){
        return $this->medias->where('sorting', 1)->first();
    }


    /**
     * 
     * Parse the created_at date
     * 
     */
    public function createdAt(){

        $postDate = $this->created_at;
        if($postDate->isToday()){
            return $postDate->format('h:m A');
        } else if($postDate->isCurrentYear()){
            return $postDate->format('jS \\of F');
        } else {
            return $postDate->format('jS \\of F Y');
        }

    }


    /**
     * 
     * Override the delete method
     * To delete the post's files
     * 
     */
    public function delete(){
        $medias = $this->medias;
        foreach($medias as $media){
            $media->delete();
        }
        $downloads = $this->downloads;
        foreach($downloads as $download){
            $download->delete();
        }
        parent::delete();
    }

    /**
     * 
     * Status numbers to text and check if it's deleted or not
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

            default:
                return 'unknown';

        }
        return 'unknown';
    }


}
