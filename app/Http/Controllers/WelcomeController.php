<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\Tag;
use Validator;
use Illuminate\Database\Eloquent\Builder;

class WelcomeController extends Controller
{

    /**
     * 
     * Index posts for the homepage
     * 
     */
    public function index(){

        $categories = Category::all();
        $posts = Post::all();
        return view('screens.main', ['categories' => $categories, 'posts' => $posts]);

    }


    /**
     * 
     * Search posts (Post Request)
     * @param Request $request
     * @return RedirectResponse
     * 
     */

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


    /**
     * 
     * Search posts (Get Request)
     * @param Request $request
     * @return RedirectResponse
     * 
     */

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

        // check if the category exists then get the posts from it
        // if not get from any category
        $category = Category::where('url', $category)->first();
        if(!empty($category)){
            $posts = $category->approvedPosts();
        }else{
            $posts = new Post();
            $category = null;
        }
        
        return $this->searchPostsAndTags($posts, $searchQuery, $category);

    }


    /**
     * 
     * Search the posts and the tags
     * @param Post
     * @param string
     * 
     */
    private function searchPostsAndTags($posts, $searchQuery, $category){

        // Explode the search query to words and search for every word
        $searchQueryArr = explode(' ', $searchQuery);
        // Get only the approved posts
        $posts = $posts->where('status', 2);


        $posts->where(function($query) use ($searchQueryArr){
            $i = 0;
            foreach($searchQueryArr as $searchQueryWord){
                if($i == 0){
                    $query->where('title', 'LIKE', '%' . $searchQueryWord . '%');
                }else{
                    $query->orWhere('title', 'LIKE', '%' . $searchQueryWord . '%');
                }
                // Search the tags too
                $query->orWhereHas('tags', function($query) use ($searchQueryWord){
                    $query->where('name', 'LIKE', '%' . $searchQueryWord . '%');
                });
                $i++;
            }

        });

        return $this->searchResults($posts, $category->id ?? 0, $searchQuery);

    }



    /**
     * 
     * Show results only from a chosen category
     * @param Request $request
     * 
     */
    public function searchCategories(Request $request){

        $category = $request->category;

        if(!empty($category) && strtolower($category) != 'all'){

            $category = Category::where('url', $category)->firstOrFail();
            $posts = $category->approvedPosts();

        } else {

            $posts = new Post();
            $posts = $posts->approvedPosts();

        }

        return $this->searchResults($posts, $category->id ?? 0);

    }


    /**
     * 
     * Show results only from a chosen tag
     * @param Request $request
     * 
     */
    public function searchTags(Request $request){

        $tag = $request->tag;

        if(!empty($tag) && strtolower($tag) != 'all'){

            $tag = Tag::where('url', $tag)->firstOrFail();
            $posts = $tag->approvedPosts();

        } else {
            $posts = new Post();
            $posts = $posts->approvedPosts();
        }

        return $this->searchResults($posts);

    }


    /**
     * 
     * Show the search results
     * @param array $posts
     * @param int $reqCategory Requested Category
     * @param string $keyword
     * @return View
     * 
     */
    public function searchResults($posts = [], $reqCategory = 0, $keyword = ''){

        if(!empty($posts)){
            $posts = $posts->with('category')->orderBy('id', 'desc')->paginate(12);
        }
        $categories = Category::all();
        $reqCategory = Category::find($reqCategory);
        return view('screens.searchResults', ['posts' => $posts, 'categories' => $categories, 'reqCategory' => $reqCategory->name ?? '', 'inputKeyword' => $keyword]);

    }

}
