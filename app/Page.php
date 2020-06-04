<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{

    use SoftDeletes;


    /**
     * 
     * Check if the page is published
     * @return bool 
     * 
     */
    public function isPublished(){
        return $this->status == 2;
    }


    /**
     * 
     * Get Active Page And Search By URL
     * @param string $url
     * @return Object
     * 
     */
    public function getPublishedPageByUrl(string $url){

        return $this->where([['status', 1], ['url', $url]])->firstOrFail();

    }



    /**
     * 
     * Status numbers to text and check if deleted or not
     * 
     */
    public function statusInText(){

        if($this->trashed()){
            return 'deleted';
        }
        switch($this->status){

            case 0:
                return 'draft';
            
            case 1:
                return 'published';

            default:
                return 'unknown';

        }
        return 'unknown';
    }
}
