<?php

namespace App\Http\Controllers;

use App\License;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;

class LicenseController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('role:moderator|admin');
    }
    

    /**
     * Index the licenses for admins.
     * @param array $license
     * @return View
     */
    public function adminIndex($licenses = null)
    {
        if(!$licenses){
            $licenses = License::orderBy('id', 'desc')->paginate(20);
        } else {
            $licenses = $licenses->paginate(20);
        }
        return view('admin.licenses.licenses', ['licenses' => $licenses]);
    }


    /**
     * Store a new license.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function adminAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:40',
            'url' => 'required|string',
            'link' => 'string|active_url|nullable',
            'description' => 'string|nullable'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        } 

        $license = new License();
        $license->name = strToLower($request->name);
        $request->url = Str::slug($request->url, '-');
        $check = License::where(['url' => $request->url])->first();
        if(!empty($check)){
            return back()->withErrors('The url has already been taken.')->withInput();
        }
        $license->url = $request->url;
        $license->link = $request->link;
        $license->description = $request->description;
        $license->save();

        return back()->with('status', 'A new licenses has been created!');
    }

    /**
     * Display the specified license.
     *
     * @param  int $id
     * @return View
     */
    public function adminShow($id)
    {
        $license = License::findOrFail($id);
        return view('admin.licenses.show', ['license' => $license]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return RedirectResponse
     */
    public function adminEdit(Request $request, $id)
    {
        $license = License::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:40',
            'url' => 'string',
            'link' => 'string|active_url|nullable',
            'description' => 'string|nullable'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        } 

        $license->name = strToLower($request->name);
        $request->url = Str::slug($request->url, '-');
        if($request->url != $license->url){
            $check = License::where(['deleted_at' => NULL, 'url' => $request->url])->first();
            if(!empty($check)){
                return back()->withErrors('The url has already been taken.')->withInput();
            }
            $license->url = $request->url;
        }
        $license->link = $request->link;
        $license->description = $request->description;
        $license->save();

        return back()->with('status', 'This license has been edited');
    }

    /**
     * Remove the specified license.
     *
     * @param  \App\License  $license
     * @return RedirectResponse
     */
    public function adminDestroy($id)
    {
        $license = License::findOrFail($id);
        $license->delete();
        return redirect('/admin/dashboard/licenses/')->with('status', 'A license has been deleted!');
    }


    /**
     * Search the licenses for admins.
     *
     * @param  Request
     * @return adminIndex()
     */

    public function adminSearchLicenses(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'name' => 'string|max:300|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect('/admin/dashboard/licenses/')->withErrors($validator)->withInput();
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

        $licenses = License::where($whereArr);

        if(empty($licenses)){
            return $this->adminIndex();
        }
        return $this->adminIndex($licenses);
    }

}
