<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DownloadFacade extends Facade{

    public static function getFacadeAccessor(){
        return \App\Services\DownloadService::class;
    }

}