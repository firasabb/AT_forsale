<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Page;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminPageController extends Controller
{

    /**
     * Index the pages for admins
     * @param string $order
     * @param bool $desc
     * @param array $pages
     * @return View
     */
    public function adminIndex($order = '', $desc = false, $pages = ''){

        // Order By Options For Filtering
        // To show all table columns use the line below and comment the second line
        //$orderByOptions = DB::getSchemaBuilder()->getColumnListing($pagesTable);
        $orderByOptions = ['id', 'title', 'status'];

        $defaultOrder = 'id';

        if(!$pages){
            if($order){
                if(in_array($order, $orderByOptions) === TRUE){
                    $defaultOrder = $order;
                }
            }
            if($desc){
                $pages = Page::orderBy($defaultOrder, 'desc');
            }
            if(!$desc){
                $pages = Page::orderBy($defaultOrder, 'asc');
            }
        }


        $pages = $pages->paginate(20);
        return view('admin.pages.pages', ['pages' => $pages, 'order' => $order, 'desc' => $desc]);
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
            'status' => ['required', 'string'],
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
    public function adminSearch(Request $request){

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
        return $this->adminIndex('', false, $pages);
    }

}
