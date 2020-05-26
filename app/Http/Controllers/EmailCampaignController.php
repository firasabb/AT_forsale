<?php

namespace App\Http\Controllers;

use App\EmailCampaign;
use Illuminate\Http\Request;

class EmailCampaignController extends Controller
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
     * 
     * Index Email Campaigns for admins and moderators
     * 
     * @param Array ? Null $emailCampaigns
     * @return Response
     * 
     */

    public function adminIndex($emailCampaigns = null)
    {
        if(!$emailCampaigns){
            $emailCampaigns = EmailCampaign::orderBy('id', 'desc')->paginate(20);
        } else {
            $emailCampaigns = $emailCampaigns->paginate(20);
        }
        return view('admin.emailcampaigns.emailcampaigns', ['emailCampaigns' => $emailCampaigns]);
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
            'name' => 'required|string|max:40'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        } 

        $emailCampaign = new EmailCampaign();
        $emailCampaign->name = $request->name;
        $emailCampaign->save();

        return back()->with('status', 'A new email campaign has been created!');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmailCampaign  $emailCampaign
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {
        $emailCampaign = EmailCampaign::findOrFail($id);
        return view('admin.emailcampaign.show', ['emailCampaign' => $emailCampaign]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmailCampaign  $emailCampaign
     * @return \Illuminate\Http\Response
     */
    public function adminEdit(Request $request, $id)
    {
        $emailCampaign = EmailCampaign::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:40'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        } 

        $emailCampaign->name = $request->name;
        $emailCampaign->save();

        return back()->with('status', 'This email campaign has been edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmailCampaign  $emailCampaign
     * @return \Illuminate\Http\Response
     */
    public function adminDestroy($id)
    {
        $emailCampaign = EmailCampaign::findOrFail($id);
        $emailCampaign->delete();
        return redirect()->route('admin.index.emailcampaigns')->with('status', 'The campaign has been deleted has been deleted!');
    }


    /**
     * Search the email campaigns for admins.
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */

    public function adminSearchEmailCampaigns(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'name' => 'string|max:300|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect()->route('admin.emailcampaigns.emailcampaigns')->withErrors($validator)->withInput();
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

        $emailCampaigns = EmailCampaign::where($whereArr);

        if(empty($emailCampaigns)){
            return $this->adminIndex();
        }
        return $this->adminIndex($emailCampaigns);
    }
}
