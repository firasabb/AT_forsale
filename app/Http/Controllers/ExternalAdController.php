<?php

namespace App\Http\Controllers;

use App\ExternalAd;
use Illuminate\Http\Request;
use Validator;

class ExternalAdController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('role:moderator|admin');
    }
    

    public function adminIndex($externalAds = null)
    {
        if(!$externalAds){
            $externalAds = ExternalAd::orderBy('id', 'desc')->paginate(20);
        } else {
            $externalAds = $externalAds->paginate(20);
        }
        return view('admin.externalads.externalads', ['externalAds' => $externalAds]);
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
            'body' => 'string|nullable'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        } 

        $externalAd = new ExternalAd();
        $externalAd->name = strToLower($request->name);
        $externalAd->body = $request->body;
        $externalAd->save();

        return back()->with('status', 'A new external ad has been created!');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExternalAd  $externalAd
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {
        $externalAd = ExternalAd::findOrFail($id);
        return view('admin.externalads.show', ['externalAd' => $externalAd]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExternalAd  $externalAd
     * @return \Illuminate\Http\Response
     */
    public function adminEdit(Request $request, $id)
    {
        $externalAd = ExternalAd::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:40',
            'body' => 'string|nullable'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        } 

        $externalAd->name = strToLower($request->name);
        $externalAd->body = $request->body;
        $externalAd->save();

        return back()->with('status', 'This external ad has been edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExternalAd  $externalAd
     * @return \Illuminate\Http\Response
     */
    public function adminDestroy($id)
    {
        $externalAd = ExternalAd::findOrFail($id);
        $externalAd->delete();
        return redirect()->route('admin.index.externalads')->with('status', 'An external ad has been deleted!');
    }


    /**
     * Search the externalAds for admins.
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */

    public function adminSearchExternalAds(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'name' => 'string|max:300|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return back()->withErrors($validator)->withInput();
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

        $externalAds = ExternalAd::where($whereArr);

        if(empty($externalAds)){
            return $this->adminIndex();
        }
        return $this->adminIndex($externalAds);
    }
}
