<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset;
use App\Category;
use Validator;

class WelcomeController extends Controller
{

    public function __construct(){

    }

    public function index(){

        $assets = Asset::orderBy('id', 'desc')->paginate(5);
        $categories = Category::all();
        return view('screens.main', ['assets' => $assets, 'categories' => $categories]);

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
                $assets = $category->approvedAssets()->where($whereArr);
            } else {
                $assets = $category->approvedAssets();
            }

        } else {

            if(!empty($whereArr)){
                $assets = Asset::where($whereArr);
            } else {
                $assets = new Asset();
                $assets = $assets->approvedAssets()->orderBy('id', 'desc');
            }

        }

        return $this->searchResults($assets);

    }


    public function searchResults($assets = []){

        if(!empty($assets)){
            $assets = $assets->with('category')->paginate(10);
        }
        $categories = Category::all();
        return view('screens.searchResults', ['assets' => $assets, 'categories' => $categories]);

    }

}
