<?php

namespace App\Services;

use App\ExternalAd;

class ExternalAdInjector{

    public function getAd(String $name) : ?String{
        $externalAd = ExternalAd::where('name', $name)->first();
        if($externalAd){
            return $externalAd->body;
        }
        return NULL;
    }

}