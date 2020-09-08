<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PageController extends Controller
{

    /**
     * Index the pages for admins
     * @param array $pages
     * @return View
     */
    public function adminIndex($pages = ''){
        if(!$pages){
            $pages = Page::orderBy('id', 'desc');
        }
        $pages = $pages->paginate(20);
        return view('admin.pages.pages', ['pages' => $pages]);
    }


    /**
     * Add a new page for admins.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function adminAdd(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'url' => ['required', 'string', 'max:50', 'unique:pages,url']
        ]);

        if($validator->fails()){
            return back()->withErrors($validator);
        }

        $page = new Page();
        $page->title = $request->title;
        $page->url = Str::slug($request->url, '-');
        $page->save();

        return back()->with('status', 'A new page has been created!');

    }


    /**
     * Show a page for admins.
     *
     * @param int $id
     * @return View
     */
    public function adminShow($id){
        $page = Page::findOrFail($id);
        return view('admin.pages.show', ['page' => $page]);
    }


    /**
     * Edit a page for admins.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function adminEdit(Request $request, $id){

        $page = Page::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'url' => ['required', 'string', 'max:50', Rule::unique('pages', 'url')->ignore($page->url, 'url')],
            'status' => ['required', 'integer'],
            'body' => ['nullable', 'string']
        ]);

        if($validator->fails()){
            return back()->withErrors($validator);
        }

        $page->title = $request->title;
        $page->url = Str::slug($request->url, '-');
        $page->body = $request->body;
        $page->status = $request->status;
        $page->save();

        return back()->with('status', 'The page has been edited');
    }


    /**
     * Delete a page for admins.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function adminDestroy($id){
        
        $page = Page::findOrFail($id);
        $page->delete();
        return redirect()->route('admin.index.pages')->with('status', 'The page has been deleted.');

    }


    /**
     * Search pages for admins.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function adminSearchPages(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'title' => 'string|max:300|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect()->route('admin.index.pages')->withErrors($validator)->withInput();
        }

        $id = $request->id;
        $title = $request->title;
        
        $where_arr = array();

        if ($id){

            $id_where = ['id', '=', $id];
            array_push($where_arr, $id_where);

        } if($title){

            $title_where = ['title', 'LIKE', '%' . $title . '%'];
            array_push($where_arr, $title_where);

        }

        $pages = Page::where($where_arr);

        if(empty($pages)){
            return $this->adminIndex();
        }
        return $this->adminIndex($pages);
    }


    /**
     * View a page.
     *
     * @param string $url
     * @return View
     */

    public function showPage($url){

        $page = new Page();
        $page = $page->getPublishedPageByUrl($url);
        return view('pages.show', ['page' => $page]);

    }

}
