<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class UserLinkFacade extends Facade{

    public static function getFacadeAccessor(){
        return \App\Services\UserLinkService::class;
    }

}