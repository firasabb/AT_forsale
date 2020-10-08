<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{

    use SoftDeletes;


    /**
     * 
     * Check If The Page is Published
     * @return bool 
     * 
     */
    public function isPublished(){
        return $this->status == 'published';
    }


    /**
     * 
     * Get Active Page And Search By URL
     * @param string $url
     * @return Page
     * 
     */
    public function getPublishedPageByUrl(string $url){

        return $this->where([['status', 'published'], ['url', $url]])->firstOrFail();

    }

}
