<?php

namespace App\Http\Controllers;

use App\Category;
use App\Media;
use Illuminate\Http\Request;
use Validator;
use URL;
use Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;



class CategoryController extends Controller
{

    /**
      * Index The Categories For Admins
      * @param Category $categories
      * @return View
      *
      */
    public function adminIndex($categories = null)
    {
        if(!$categories){
            $categories = Category::orderBy('id', 'desc')->paginate(20);
        } else {
            $categories = $categories->paginate(20);
        }
        $parentCategories = new Category();
        $parentCategories = $parentCategories->parentCategories();
        return view('admin.categories.categories', ['categories' => $categories, 'parentCategories' => $parentCategories]);
    }


    /**
      * Add a Category For Admins
      * @param Request $request
      * @return RedirectResponse
      *
      */
    public function adminAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'url' => 'required|string',
            'parent_id' => 'integer|nullable',
            'featured' => 'file|mimes:png,jpg|max:2000|nullable'
        ]);

        if($validator->fails()){
            return redirect('/admin/dashboard/categories')->withErrors($validator)->withInput();
        } 

        $category = new Category();
        $category->name = strToLower($request->name);
        $request->url = Str::slug($request->url, '-');
        $check = Category::where(['deleted_at' => NULL, 'url' => $request->url])->first();
        if(!empty($check)){
            return redirect('/admin/dashboard/categories/')->withErrors('The url has already been taken.')->withInput();
        }
        $category->url = $request->url;

        $parentId = $request->parent_id;
        if($parentId){
            $parentCategory = Category::findOrFail($parentId);
            $category->parent_id = $parentId;
        }

        $category->save();

        if($request->featured){
            $media = new Media();
            $uploadedFile = $request->featured;
            $unique = uniqid();
            $path = $uploadedFile->storePublicly('media/' . $unique ,'s3');
            $media->url = $path;
            $media->public_url = Storage::url($path);
            $media->sorting = 1;
            $media->save();
            $category->medias()->attach($media);
        }

        return redirect('/admin/dashboard/categories')->with('status', 'A new category has been created!');


    }


    /**
      * Edit a Category For Admins
      * @param Request $request
      * @param int $id
      * @return RedirectResponse
      *
      */
    public function adminEdit(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:40',
            'url' => 'string',
            'parent_id' => 'nullable|integer',
            'featured' => 'file|mimes:png,jpg|max:2000|nullable'
        ]);

        if($validator->fails()){
            return redirect('/admin/dashboard/category/' . $id)->withErrors($validator)->withInput();
        } 

        $category->name = strToLower($request->name);
        $request->url = Str::slug($request->url, '-');
        if($request->url != $category->url){
            $check = Category::where(['deleted_at' => NULL, 'url' => $request->url])->first();
            if(!empty($check)){
                return redirect('/admin/dashboard/category/' . $id)->withErrors('The url has already been taken.')->withInput();
            }
            $category->url = $request->url;
        }

        $parentId = $request->parent_id;
        if($parentId){
            $parentCategory = Category::findOrFail($parentId);
            $category->parent_id = $parentId;
        } else {
            $category->parent_id = null;
        }

        $category->save();

        if($request->featured){
            $unique = uniqid();
            $uploadedFile = $request->featured;
            if(!empty($category->medias->first())){
                $media = $category->medias()->first();
                $path = $uploadedFile->storePublicly('media/' . $unique, 's3');
                $media->url = $path;
                $media->public_url = Storage::url($path);
                $media->save();
            } else {
                $media = new Media();
                $path = $uploadedFile->storePublicly('media/' . $unique, 's3');
                $media->url = $path;
                $media->public_url = Storage::url($path);
                $media->sorting = 1;
                $media->save();
                $category->medias()->attach($media);
            }
        }


        return redirect('/admin/dashboard/category/' . $id)->with('status', 'This category has been edited');
    }


    /**
      * Show a Category Info For Admins
      * @param int $id
      * @return View
      *
      */
    public function adminShow($id)
    {
        $category = Category::findOrFail($id);
        $parentCategories = new Category();
        $parentCategories = $parentCategories->parentCategories();
        return view('admin.categories.show', ['category' => $category, 'parentCategories' => $parentCategories]);
    }


    /**
      * Delete a Category For Admins
      * @param int $id
      * @return RedirectResponse
      *
      */
    public function adminDestroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect('/admin/dashboard/categories/')->with('status', 'A category has been deleted!');
    }


    /**
      * Search Categories For Admins
      * @param Request $request
      * @return adminindex()
      *
      */
    public function adminSearchCategories(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'name' => 'string|max:300|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect('/admin/dashboard/categories/')->withErrors($validator)->withInput();
        }

        $id = $request->id;
        $name = $request->name;
        
        $where_arr = array();

        if ($id){

            $id_where = ['id', '=', $id];
            array_push($where_arr, $id_where);

        } if($name){

            $name_where = ['name', 'LIKE', '%' . $name . '%'];
            array_push($where_arr, $name_where);

        }

        $categories = Category::where($where_arr);

        if(empty($categories)){
            return $this->adminIndex();
        }
        return $this->adminIndex($categories);
    }

}
