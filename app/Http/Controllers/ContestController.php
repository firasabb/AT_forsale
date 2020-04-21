<?php

namespace App\Http\Controllers;

use App\Contest;
use App\Category;
use Illuminate\Http\Request;

class ContestController extends Controller
{

    public function __construct(){

        $this->middleware('auth');

    }


    /**
     * 
     * Show Contests In The Admin Panel
     * @param Array Contests
     * @return View
     * 
     */
    public function adminIndex($contests = null)
    {
        if(!$contests){
            $contests = Contest::orderBy('id', 'desc')->paginate(10);
        } else {
            $contests = $contests->paginate(20);
        }
        return view('admin.contests.contests', ['contests' => $contests]);
    }


    public function adminShow($id)
    {
        $contest = Contest::findOrFail($id);
        $categories = Category::all();
        $categories = Categories::all();
        $hasCategories = $contest->categories->pluck('id');
        return view('admin.contests.show', ['contest' => $contest, 'categories' => $categories, 'hasCategories' => $hasCategories, 'categories' => $categories]);
    }


    /**
     * 
     * Update the contest
     * @param request
     * @return response
     * 
     */
    public function adminEdit(Request $request, $id)
    {
        $contest = Contest::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:15|max:200',
            'description' => 'string|max:500|nullable',
            'categories' => 'required|array',
            'categories.*' => 'integer',
            'options' => 'array',
            'options.*' => 'string|max:200',
            'tags' => 'string|max:150',
            'category_id' => 'integer'
        ]);

        if($validator->fails()){
            return redirect()->route('admin.show.contest', ['id' => $id])->withErrors($validator)->withInput();
        } 

        $contest->title = $request->title;
        $contest->url = $request->url;
        $contest->description = $request->description;
        $category = Category::findOrFail($request->category_id);
        $contest->category()->associate($category);
        $contest->categories()->sync($request->categories);
        $tagsArr = array();
        $tags = $request->tags;
        $tags = explode(', ', $tags);
        foreach($tags as $tag){
            $tag = Tag::where('name', 'LIKE', $tag)->first();
            array_push($tagsArr, $tag->id);
        }
        $contest->tags()->sync($tagsArr);
        //$contest->options = $request->options;
        $contest->save();

        return redirect()->route('admin.show.contest', ['id' => $id])->with('status', 'This comment has been edited');
    }


    public function adminDestroy($id)
    {
        $contest = Contest::findOrFail($id);
        $downloads = $contest->downloads;
        foreach($downloads as $download){
            Storage::cloud()->delete($download->url);
        }
        $cover = $contest->covers->first();
        if(!empty($cover)){
            Storage::cloud()->delete($cover->url);
        }
        $contest->delete();
        return redirect('/admin/dashboard/contests/')->with('status', 'The contest has been deleted!');
    }


    public function adminSearchContests(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'title' => 'string|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect()->route('admin.index.contests')->withErrors($validator)->withInput();
        }

        $id = $request->id;
        $title = $request->title;
        $url = Str::slug($request->url);
        
        $where_arr = array();

        if($id){

            $id_where = ['id', '=', $id];
            array_push($where_arr, $id_where);

        } if($title){

            $title_where = ['title', 'LIKE', '%' . $title . '%'];
            array_push($where_arr, $title_where);

        } if($url){

            $url_where = ['url', 'LIKE', '%' . $url . '%'];
            array_push($where_arr, $url_where);

        }

        $contests = Contest::where($where_arr);

        if(empty($contests)){
            return $this->adminIndex();
        }
        return $this->adminIndex($contests);
    }




}
