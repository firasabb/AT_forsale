<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset;
use App\Category;
use App\Tag;
use Validator;
use Illuminate\Database\Eloquent\Builder;

class WelcomeController extends Controller
{

    public function __construct(){

    }

    public function index(){

        $stockPhotoCat = Category::where('name', 'LIKE' ,'stock photos')->first();
        $stockPhotoAssets = $stockPhotoCat->approvedAssets()->orderBy('id', 'desc')->take(9)->get();
        $soundEffectCat = Category::where('name', 'LIKE' ,'sound effects')->first();
        $soundEffectAssets = $soundEffectCat->approvedAssets()->orderBy('id', 'desc')->take(9)->get();
        $categories = Category::all();
        return view('screens.main', ['stockPhotoAssets' => $stockPhotoAssets, 'soundEffectAssets' => $soundEffectAssets, 'categories' => $categories]);

    }


    public function searchPost(Request $request){

        $validator = Validator::make($request->all(), [
            'keyword' => 'string|nullable|max:50',
            'category' => 'string|nullable|max:50'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        $category = $request->category ?? 'all';
        $keyword = $request->keyword;

        if($keyword){
            return redirect()->route('main.get.search', ['category' => $category, 'keyword' => $keyword]);
        }

        return redirect()->route('main.search.categories', ['category' => $category]);

    }



    public function searchGet(Request $request){


        $validator = Validator::make($request->all(), [
            'keyword' => 'string|nullable|max:50',
            'category' => 'string|nullable|max:50'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        $searchQuery = $request->keyword;
        $searchQuery = trim($searchQuery);
        $category = $request->category;

        return $this->searchCheckCategory($category, $searchQuery);

    }


    /**
     * 
     * Start check if the search query has categories
     * @param Category
     * @param string 
     * 
     */
    private function searchCheckCategory($category, $searchQuery){

        // check if the category exists then get the assets from it
        // if not get from any category
        $category = Category::where('url', $category)->first();
        if(!empty($category)){
            $assets = Asset::where('category_id', $category->id);
        }else{
            $assets = new Asset();
        }
        
        return $this->searchAssetsAndTags($assets, $searchQuery, $category);

    }


    /**
     * 
     * Search the assets and the tags
     * @param Asset
     * @param string
     * 
     */
    private function searchAssetsAndTags($assets, $searchQuery, $category){

        // Explode the search query to words and search for every word
        $searchQueryArr = explode(' ', $searchQuery);
        // Get only the approved assets
        $assets = $assets->where('status', 2);

        $i = 0;
        foreach($searchQueryArr as $searchQueryWord){
            if($i == 0){
                $assets = $assets->where('title', 'LIKE', '%' . $searchQueryWord . '%');
            }else{
                $assets = $assets->orWhere('title', 'LIKE', '%' . $searchQueryWord . '%');
            }
            $i++;
        }
        return $this->searchResults($assets, $category->id ?? 0, $searchQuery);

    }



    /**
     * 
     * Show results from a chosen category
     * @param Request $request
     * 
     */
    public function searchCategories(Request $request){

        $category = $request->category;

        if(!empty($category) && strtolower($category) != 'all'){

            $category = Category::where('url', $category)->firstOrFail();
            $assets = $category->approvedAssets();

        } else {

            $assets = new Asset();
            $assets = $assets->approvedAssets();

        }

        return $this->searchResults($assets, $category->id ?? 0);

    }


    /**
     * 
     * Show results from a chosen tag
     * @param Request $request
     * 
     */
    public function searchTags(Request $request){

        $tag = $request->tag;

        if(!empty($tag) && strtolower($tag) != 'all'){

            $tag = Tag::where('url', $tag)->firstOrFail();
            $assets = $tag->approvedAssets();

        } else {
            $assets = new Asset();
            $assets = $assets->approvedAssets();
        }

        return $this->searchResults($assets);

    }



    public function searchResults($assets = [], $reqCategory = 0, $keyword = ''){

        if(!empty($assets)){
            $assets = $assets->with('category')->orderBy('id', 'desc')->paginate(12);
        }
        $categories = Category::all();
        $reqCategory = Category::find($reqCategory);
        return view('screens.searchResults', ['assets' => $assets, 'categories' => $categories, 'reqCategory' => $reqCategory->name ?? '', 'inputKeyword' => $keyword]);

    }

}
