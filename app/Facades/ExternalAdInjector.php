<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ExternalAdInjector extends Facade{

    public static function getFacadeAccessor(){
        return \App\Services\ExternalAdInjector::class;
    }

}