<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Category;
use Illuminate\Http\Request;
use Validator;
use URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderBy('id', 'desc')->paginate(20);
        return view('tags.tags', ['tags' => $tags]);
    }


    public function adminIndex($tags = null)
    {
        if(!$tags){
            $tags = Tag::orderBy('id', 'desc')->paginate(20);
        } else {
            $tags = $tags->paginate(20);
        }
        $categories = category::all();
        return view('admin.tags.tags', ['tags' => $tags, 'categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adminAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:40',
            'url' => 'required|string',
            'categories' => 'array|nullable',
            'categories.*' => 'integer' 
        ]);

        if($validator->fails()){
            return redirect('/admin/dashboard/tags')->withErrors($validator)->withInput();
        } 

        $tag = new Tag();
        $tag->name = strToLower($request->name);
        $request->url = Str::slug($request->url, '-');
        $check = Tag::where(['url' => $request->url])->first();
        if(!empty($check)){
            return redirect('/admin/dashboard/tags/')->withErrors('The url has already been taken.')->withInput();
        }
        $tag->url = $request->url;
        $tag->save();

        $categories = $request->categories;
        if(!empty($categories)){
            foreach($categories as $category){
                $category = category::findOrFail($category);
                $category->tags()->attach($tag);
            }
        }

        return redirect('/admin/dashboard/tags')->with('status', 'A new tag has been created!');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {
        $tag = Tag::findOrFail($id);
        $categories = Category::all();
        $tagCategories = $tag->categories->pluck('id');
        return view('admin.tags.show', ['tag' => $tag, 'categories' => $categories, 'tagCategories' => $tagCategories]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function adminEdit(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:40',
            'url' => 'string',
            'categories' => 'array|nullable',
            'categories.*' => 'integer'
        ]);

        if($validator->fails()){
            return redirect('/admin/dashboard/tag/' . $id)->withErrors($validator)->withInput();
        } 

        $tag->name = strToLower($request->name);
        $request->url = Str::slug($request->url, '-');
        if($request->url != $tag->url){
            $check = Tag::where(['url' => $request->url])->first();
            if(!empty($check)){
                return redirect('/admin/dashboard/tag/' . $id)->withErrors('The url has already been taken.')->withInput();
            }
            $tag->url = $request->url;
        }
        $tag->save();

        $categories = $request->categories;
        $tag->categories()->sync($categories);

        return redirect('/admin/dashboard/tag/' . $id)->with('status', 'This tag has been edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function adminDestroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return redirect('/admin/dashboard/tags/')->with('status', 'A tag has been deleted!');
    }


    /**
     * Search the tags for admins.
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */

    public function adminSearchTags(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'name' => 'string|max:300|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect('/admin/dashboard/tags/')->withErrors($validator)->withInput();
        }

        $name = $request->name;
        $id = $request->id;
        
        $whereArr = array();

        if($name){

            $name_where = ['name', 'LIKE', '%' . $name . '%'];
            array_push($whereArr, $name_where);

        } if ($id){

            $id_where = ['id', '=', $id];
            array_push($whereArr, $id_where);

        }

        $tags = Tag::where($whereArr);

        if(empty($tags)){
            return $this->adminIndex();
        }
        return $this->adminIndex($tags);
    }



    /**
     * 
     * Show add tags in bulk form
     * 
     */
    public function adminBulkAddForm(){

        $categories = Category::all();
        return view('admin.tags.addBulk', ['categories' => $categories]);
    
    }


    /**
     * 
     * Add tags in bulk
     * @param Request $request
     * 
     */
    public function adminBulkAdd(Request $request){

        $validator = Validator::make($request->all(), [
            'names' => 'required|array',
            'names.*' => ['string', 'max:50', Rule::unique('tags', 'name'), Rule::unique('tags', 'url')],
            'categories' => 'array|nullable',
            'categories.*' => 'integer'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }


        foreach($request->names as $name){
            $tag = new Tag;
            $tag->name = $name;
            $url = Str::slug($name, '-');
            // Check if the url exists
            $check = Tag::where('url', $url)->first();
            if(!empty($check)){
                return back()->withErrors('A url exists with the same name.');
            }
            $tag->url = $url;
            $tag->save();
            $categories = $request->categories;
            if(!empty($categories)){
                foreach($categories as $category){
                    $category = category::findOrFail($category);
                    $category->tags()->attach($tag);
                }
            }
        }

        return redirect()->route('admin.index.tags')->with('status', 'Tags have been created successfully');

    }


    /**
     * 
     * Search For Tags in AJAX Request
     * @param Request
     * @return Response
     * 
     */
    public function suggestTags(Request $request){

        if($request->ajax()){

            $validator = Validator::make($request->all(), [
                'tag' => 'string|nullable',
                'exist' => 'array|nullable',
                'category' => 'integer|nullable'
            ]);

            if($validator->fails()){
                $response = array(
                    'status' => 'error',
                    'response' => $validator
                );
                return response()->json($response);
            }

            $tag = $request->tag;
            $exist = $request->exist;
            $category = $request->category;

            $whereArr = array();
            if($exist){
                foreach($exist as $existTag){
                    $where = ['name', '!=', $existTag];
                    array_push($whereArr, $where);
                }
            }
            if(!empty($tag)){
                $where = ['name', 'LIKE', '%' . $tag . '%'];
                array_push($whereArr, $where);
            }
            if($category){
                $category = Category::find($category);
                $searchResults = $category->tags()->where($whereArr)->get();
            } else {
                $searchResults = Tag::where($whereArr)->get();
            }

            $response = array(
                'status' => 'success',
                'results' => $searchResults
            );
    
            return response()->json($response);
        }
        
    }


}
