<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Art;
use App\Type;
use Validator;

class WelcomeController extends Controller
{

    public function __construct(){

    }

    public function index(){

        $arts = Art::orderBy('id', 'desc')->paginate(5);
        $types = Type::all();
        return view('main', ['arts' => $arts, 'types' => $types]);

    }

    public function search(Request $request){


        $validator = Validator::make($request->all(), [
            'search' => 'string|nullable|max:50',
            'type' => 'string|nullable|max:50'
        ]);

        if($validator->fails()){
            return redirect()->route('main.searchResults');
        }

        $search_query = $request->search;
        $type = $request->type;

        $whereArr = array();

        if(!empty($search_query) && strtolower($search_query) != 'all'){

            $whereArr[] = ['title', 'LIKE', '%' . $search_query . '%'];
            $whereArr[] = ['status', 2];

        }

        if(!empty($type) && strtolower($type) != 'all'){

            $type = Type::where('name', $type)->firstOrFail();
            if(!empty($whereArr)){
                $arts = $type->approvedArts()->where($whereArr);
            } else {
                $arts = $type->approvedArts();
            }

        } else {

            if(!empty($whereArr)){
                $arts = Art::where($whereArr);
            } else {
                $arts = Art::where('status', 2)->orderBy('id', 'desc');
            }

        }

        return $this->searchResults($arts);

    }


    public function searchResults($arts = []){

        if(!empty($arts)){
            $arts = $arts->paginate(10);
        }
        $types = Type::all();
        return view('searchResults', ['arts' => $arts, 'types' => $types]);

    }

}
