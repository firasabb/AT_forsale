<?php

namespace App\Http\Controllers;

use App\UserAd;
use App\Media;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Storage;

class UserAdController extends Controller
{
    /**
     * 
     * Constructor
     * 
     */

    public function __construct(){
        return $this->middleware('role:user');
    }


    /**
     * 
     * Store a new user's ad
     * @param Request
     * @return Response
     * 
     */

    public function storeAjax(Request $request){

        $validator = Validator::make($request->all(), [
            'header_title' => ['string', 'nullable', 'max:50'],
            'appreciation_msg' => ['string', 'nullable', 'max:100'],
            'first_btn' => ['string', 'nullable', 'exists:user_links,name'],
            'second_btn' => ['string', 'nullable', 'exists:user_links,name'],
            'paypal_donation' => ['boolean', 'nullable'],
            'image_url' => ['active_url', 'nullable'],
            'upload' => ['image', 'max:2000', 'clamav'],
            'ad_url' => ['active_url', 'nullable']
        ]);

        if($validator->fails()){

            if($request->ajax()){
                $response = array(
                    'status' => 'error',
                    'response' => $validator
                );
                return response()->json($response);
            } else {
                return back()->withErrors($validator);
            }
        }

        $header_title = $request->header_title;
        $appreciation_msg = $request->appreciation_msg;
        $first_btn = $request->first_btn;
        $second_btn = $request->second_btn;
        $paypal_donation = $request->paypal_donation;
        $image_url = $request->image_url;
        $upload = $request->upload;
        $ad_url = $request->ad_url;

        // check if user has a previous ad
        $user = Auth::user();
        $ad = $user->userAds()->first();
        if(empty($ad)){
            $ad = new UserAd();   
        }
        
        $path = '';

        // if a new uploaded image or url delete the previous media files
        // To create a new one
        // else get the last upload
        if($upload || $image_url){
            $medias = $ad->medias()->get();
            foreach($medias as $media){
                $media->delete();
            }
        } else {
            $media = $ad->medias()->first();
            if(!empty($media)){
                $path = $media->public_url;
            }
        }

        if($upload){
            try{
                $awsPath = Storage::cloud()->putFile('media', $upload, 'public');
            } catch(Exception $e){
                if($request->ajax()){
                    $response = array(
                        'status' => 'error',
                        'response' => 'A problem was occured while trying to upload the image... please try again later'
                    );
                    return response()->json($response);
                } else {
                    return back()->withErrors('A problem was occured while trying to upload the image... please try again later');
                }
            }
            $path = Storage::cloud()->url($awsPath);
            $media = new Media();
            $media->sorting = 3;
            $media->url = $awsPath;
            $media->public_url = $path;
        }

        $contentArr = array('header_title' => $header_title,
        'appreciation_msg' => $appreciation_msg,
        'first_btn' => $first_btn,
        'second_btn' => $second_btn,
        'paypal_donation' => $paypal_donation,
        'image_url' => $image_url,
        'upload' => $path,
        'ad_url' => $request->ad_url
        );

        $ad->content = serialize($contentArr);

        $render = $this->renderModal($header_title,
        $appreciation_msg,
        $first_btn, 
        $second_btn, 
        $paypal_donation, 
        $image_url, 
        $path, 
        $ad_url, 
        $user);

        $ad->status = 1;

        $user->userAds()->save($ad);

        if(isset($media)){
            $ad->medias()->save($media);
        }

        if($request->ajax()){
            $response = array(
                'status' => 'success',
                'response' => $render
            );
        } else {
            return back();
        }   

        return response()->json($response);
        

    }

    public function deleteAdMediasAjax(Request $request){

        $ad = UserAd::findOrFail(decrypt($request->ad_id));
        $medias = $ad->medias()->get();
        foreach($medias as $media){
            $media->delete();
        }

        $user = $ad->user();

        $content = unserialize($ad->content);

        $content['image_url'] = '';
        $content['upload'] = '';
        $content['ad_url'] = '';

        $ad->content = serialize($content);

        $render = $this->renderModal($content['header_title'],
        $content['appreciation_msg'],
        $content['first_btn'], 
        $content['second_btn'], 
        $content['paypal_donation'], 
        '', 
        '', 
        '', 
        $user);

        $ad->save();

        if($request->ajax()){
            $response = array(
                'status' => 'success',
                'response' => $render
            );
        } else {
            return back();
        }

        return response()->json($response);

        
    }

    /**
     * 
     * Show the approve page
     * @return Response
     * 
     */
     public function indexToApprove(){
         $ads = UserAd::where('status', 1)->orderBy('id', 'desc')->paginate(1);
         return view('admin.userads.indexToApprove', ['ads' => $ads]);
     }


    /**
     * 
     * Approve the user ad
     * @param Request $request
     * @param Request $id
     * @return Response
     * 
     */
    public function adminApprove(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'header_title' => 'nullable|string',
            'appreciation_msg' => 'nullable|string'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator);
        }

        $userAd = UserAd::findOrFail($id);
        $userAd->status = 2;
        $content = unserialize($userAd->content);
        $content['header_title'] = $request->header_title;
        $content['appreciation_msg'] = $request->appreciation_msg;
        $userAd->content = serialize($content);
        $userAd->save();

        return back()->with('status', 'The ad is approved!');
    }


    /**
     * 
     * Disapprove the user ad
     * @param Request $request
     * @param Request $id
     * @return Response
     * 
     */
    public function adminDisapprove($id){

        $userAd = UserAd::findOrFail($id);
        $userAd->status = 0;
        $userAd->save();

        return back()->with('status', 'The ad is disapproved!');
    }


    /**
     * Admin / Moderator Index User Ads
     * @param Array $userAds
     * @return Response
     */

    public function adminIndex($userAds = null)
    {
        if(!$userAds){
            $userAds = UserAd::orderBy('id', 'desc')->paginate(20);
        } else {
            $userAds = $userAds->paginate(20);
        }
        return view('admin.userads.userads', ['userAds' => $userAds]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\UserAd  $userAd
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {
        $userAd = UserAd::findOrFail($id);
        return view('admin.userads.show', ['userAd' => $userAd]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserAd  $userAd
     * @return \Illuminate\Http\Response
     */
    public function adminDestroy($id)
    {
        $userAd = UserAd::findOrFail($id);
        $userAd->delete();
        return redirect()->route('admin.userads.userads')->with('status', 'The ad has been deleted has been deleted!');
    }


    /**
     * Search users ads for admins.
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */

    public function adminSearchUserAds(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'name' => 'string|max:300|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect()->route('admin.userads.userads')->withErrors($validator)->withInput();
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

        $userAds = UserAd::where($whereArr);

        if(empty($userAds)){
            return $this->adminIndex();
        }
        return $this->adminIndex($userAds);
    }



    /**
     * Make an html modal from variables
     */
    private function renderModal($title, $msg, $btn_1, $btn_2, $paypal_donation, $img_url, $img_path, $ad_url, $user){

        // opening modal
        $html = '<div id="userAdModal" tabindex="-1" role="dialog" aria-labelledby="userAdLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">';
        // opening modal header
        $html .= '<div class="modal-header">';

        // if title exists add it to header
        if($title){
            $html .= '<h5 class="modal-title" id="exampleModalLabel">' . htmlspecialchars($title) . '</h5>';
        }
        
        // Add the closing btn
        $html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>';

        // closing header
        $html .= '</div>';

        // opening modal body
        $html .= '<div class="modal-body">';

        // if appreciation message exists add it to the body
        if($msg){
            $html .= '<div class="row">
                        <div class="col text-center">
                            <div class="py-2">
                                <h5>' . htmlspecialchars($msg) . '</h5>
                            </div>
                        </div>
                    </div>';
        }

        // If paypal donations
        if($paypal_donation){
            $html .= '<div class="row justify-content-center">
                <div class="col text-center">
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                        <input type="hidden" name="cmd" value="_donations" />
                        <input type="hidden" name="business" value="' . htmlspecialchars($user->paypal) . '" />
                        <input type="hidden" name="currency_code" value="USD" />
                        <button type="submit" class="btn btn-outline-custom-light">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512; width: 20px" xml:space="preserve">
                            <path style="fill:#002987;" d="M428.876,132.28c0.867-7.045,1.32-14.218,1.32-21.497C430.196,49.6,380.597,0,319.413,0H134.271  c-11.646,0-21.589,8.41-23.521,19.894l-68.22,405.475c-2.448,14.55,8.768,27.809,23.521,27.809h67.711  c11.646,0,21.776-8.404,23.707-19.889c0,0,0.113-0.673,0.317-1.885h0.001l-9.436,56.086C146.195,500.313,156.08,512,169.083,512  h59.237c10.265,0,19.029-7.413,20.731-17.535l16.829-100.02c2.901-17.242,17.828-29.867,35.311-29.867h15.562  c84.53,0,153.054-68.525,153.054-153.054C469.807,178.815,453.639,149.902,428.876,132.28z"></path>
                            <path style="fill:#0085CC;" d="M428.876,132.28c-10.594,86.179-84.044,152.91-173.086,152.91h-51.665  c-11.661,0-21.732,7.767-24.891,18.749l-30.882,183.549C146.195,500.312,156.08,512,169.083,512h59.237  c10.265,0,19.029-7.413,20.731-17.535l16.829-100.02c2.901-17.242,17.828-29.867,35.311-29.867h15.562  c84.53,0,153.054-68.525,153.054-153.054l0,0C469.807,178.815,453.639,149.902,428.876,132.28z"></path>
                            <path style="fill:#00186A;" d="M204.125,285.19h51.665c89.043,0,162.493-66.731,173.086-152.909  c-15.888-11.306-35.304-17.978-56.29-17.978h-134.85c-15.353,0-28.462,11.087-31.01,26.227l-27.493,163.408  C182.392,292.956,192.464,285.19,204.125,285.19z"></path>
                        </svg>  Donate</button>
                        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                    </form>
                </div>
            </div>';
        }

        // If one of the buttons exists add a row
        if($btn_1 || $btn_2){
            // open a row tag
            $html .= '<div class="row justify-content-center">';

                // If two buttons are set
                if($btn_1 && $btn_2){
                    $html .= '<div class="col text-center">
                        <a class="btn btn-primary" target="_blank" href="#"></a>
                    </div>
                    <div class="col text-center">
                        <a class="btn btn-primary" target="_blank" href="#"></a>
                    </div>';
                }
                // If one button only is set
                else if ($btn_1 xor $btn_2){
                    $btn = $btn_1 ?? $btn_2;
                    $html .= '<div class="col text-center">
                        <a class="btn btn-primary" target="_blank" href="#"></a>
                    </div>';
                }
            // close the row tag
            $html .= '</div>';
        }

        // If there is an image url or an uploaded image path
        if($img_url || $img_path){
            $img = $img_url ?? $img_path;
            $url = $ad_url ?? '#';
            $html .= '<div class="row">
                <div class="col text-center">
                    <a href="' . htmlspecialchars($url) . '" target="_blank">
                        <img style="width: 100%" src="' . htmlspecialchars($img) . '">
                    </a>
                </div>
            </div>';

        }

        // closing modal body
        $html .= '</div>';

        // closing modal
        $html .= '</div></div></div>';

        return $html;
    }
}
