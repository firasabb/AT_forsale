<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    /**
     * Get the owning optionable model.
     */

    public function optionable(){
        return $this->morphTo();
    }

}
