<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Art;
use App\Category;
use Validator;

class WelcomeController extends Controller
{

    public function __construct(){

    }

    public function index(){

        $arts = Art::orderBy('id', 'desc')->paginate(5);
        $categories = Category::all();
        return view('main', ['arts' => $arts, 'categories' => $categories]);

    }

    public function search(Request $request){


        $validator = Validator::make($request->all(), [
            'search' => 'string|nullable|max:50',
            'category' => 'string|nullable|max:50'
        ]);

        if($validator->fails()){
            return redirect()->route('main.searchResults');
        }

        $search_query = $request->search;
        $category = $request->category;

        $whereArr = array();

        if(!empty($search_query) && strtolower($search_query) != 'all'){

            $whereArr[] = ['title', 'LIKE', '%' . $search_query . '%'];
            $whereArr[] = ['status', 2];

        }

        if(!empty($category) && strtolower($category) != 'all'){

            $category = Category::where('name', $category)->firstOrFail();
            if(!empty($whereArr)){
                $arts = $category->approvedArts()->where($whereArr);
            } else {
                $arts = $category->approvedArts();
            }

        } else {

            if(!empty($whereArr)){
                $arts = Art::where($whereArr);
            } else {
                $arts = new Art();
                $arts = $arts->approvedArts()->orderBy('id', 'desc');
            }

        }

        return $this->searchResults($arts);

    }


    public function searchResults($arts = []){

        if(!empty($arts)){
            $arts = $arts->with('category')->paginate(10);
        }
        $categories = Category::all();
        return view('searchResults', ['arts' => $arts, 'categories' => $categories]);

    }

}
