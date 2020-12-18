<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ContactMessage;
use Illuminate\Http\Request;
use Validator;
use App\Services\Recaptcha;

class AdminContactMessageController extends Controller
{

    /**
      * Index The Messages For Admins
      * @param Category $contactMessages
      * @return View
      *
      */
    public function adminIndex($contactMessages = null)
    {
        if(!$contactMessages){
            $contactMessages = ContactMessage::orderBy('id', 'desc')->paginate(20);
        } else {
            $contactMessages = $contactMessages->paginate(20);
        }
        return view('admin.contactmessages.contactmessages', ['messages' => $contactMessages]);
    }

    /**
     * Display a Contact Message
     * @param  \App\ContactMessage  $contactMessage
     * @return View
     */
    public function adminShow($id)
    {
        $contactMessage = ContactMessage::findOrFail($id);
        return view('admin.contactmessages.show', ['message' => $contactMessage]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ContactMessage  $contactMessage
     * @return \Illuminate\Http\Response
     */
    public function adminDestroy($id)
    {
        $contactMessage = ContactMessage::findOrFail($id);
        $contactMessage->delete();
        return redirect()->route('admin.index.contactmessages')->with('status', 'A message has been deleted!');
    }


    /**
     * Search the ContactMessages For Admins
     * @param  Request
     * @return adminIndex()
     */

    public function adminSearch(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'title' => 'string|max:300|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return back()->withErrors($validator)->withInput();
        }

        $title = $request->title;
        $id = $request->id;
        $whereArr = array();

        if($title){
            $title_where = ['title', 'LIKE', '%' . $title . '%'];
            array_push($whereArr, $name_where);
        } if ($id){
            $id_where = ['id', '=', $id];
            array_push($whereArr, $id_where);
        }
        $contactMessages = ContactMessage::where($whereArr);
        if(empty($contactMessages)){
            return $this->adminIndex();
        }
        return $this->adminIndex($contactMessages);
    }
}
