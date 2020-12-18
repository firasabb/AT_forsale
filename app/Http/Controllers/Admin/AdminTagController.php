<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Tag;
use App\Category;
use Illuminate\Http\Request;
use Validator;
use URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminTagController extends Controller
{

    /**
     * Index tags for admins.
     * @param array $tags.
     * @return View
     */
    public function adminIndex($order = '', $desc = false, $tags = null)
    {

        // Order By Options For Filtering
        // To show all table columns use the line below and comment the second line
        //$orderByOptions = DB::getSchemaBuilder()->getColumnListing($tagsTable);
        $orderByOptions = ['id', 'name'];

        $defaultOrder = 'id';

        if(!$tags){
            if($order){
                if(in_array($order, $orderByOptions) === TRUE){
                    $defaultOrder = $order;
                }
            }
            if($desc){
                $tags = Tag::orderBy($defaultOrder, 'desc');
            }
            if(!$desc){
                $tags = Tag::orderBy($defaultOrder, 'asc');
            }

        }

        $tags = $tags->paginate(20);

        $categories = category::all();
        return view('admin.tags.tags', ['tags' => $tags, 'categories' => $categories, 'order' => $order, 'desc' => $desc]);
    }


    /**
     * Add a tag for admins.
     * @param Request $request
     * @return RedirectResponse
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
     * Display a tag for admins.
     * @param int $id
     * @return View
     */
    public function adminShow($id)
    {
        $tag = Tag::findOrFail($id);
        $categories = Category::all();
        $tagCategories = $tag->categories->pluck('id');
        return view('admin.tags.show', ['tag' => $tag, 'categories' => $categories, 'tagCategories' => $tagCategories]);
    }


    /**
     * Edit the tag for admins.
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
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
     * Delete a tag for admins
     * @param int $id
     * @return RedirectResponse
     */
    public function adminDestroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return redirect('/admin/dashboard/tags/')->with('status', 'A tag has been deleted!');
    }


    /**
     * Search the tags for admins.
     * @param  Request
     * @return adminIndex()
     */

    public function adminSearch(Request $request){

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
        return $this->adminIndex('', false, $tags);
    }



    /**
     * 
     * Display bull tags add for admins
     * @return View
     * 
     */
    public function adminBulkAddForm(){

        $categories = Category::all();
        return view('admin.tags.addBulk', ['categories' => $categories]);
    
    }


    /**
     * 
     * Store tags in bulk.
     * @param Request $request
     * @return RedirectResponse
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


}
