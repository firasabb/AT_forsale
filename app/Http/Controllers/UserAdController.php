<?php

namespace App\Http\Controllers;

use App\UserAd;
use Illuminate\Http\Request;
use Auth;


class UserAdController extends Controller
{
    /**
     * 
     * Constructor
     * 
     */

    public function __construct(){
        return $this->middleware('role:user');
    }


    /**
     * 
     * Store a new user's ad
     * @param Request
     * @return Response
     * 
     */

    public function store(Request $request){


    }

}
