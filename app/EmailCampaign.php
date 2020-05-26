<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailCampaign extends Model
{
    use softDeletes;
    
    public function users(){
        return $this->belongsToMany('App\User');
    }
}
