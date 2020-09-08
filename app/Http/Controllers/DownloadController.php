<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Download;
use App\DownloadEvent;
use App\Post;
use Auth;
use Storage;
use Validator;
use Carbon\Carbon;
use App\Services\Recaptcha;
use Illuminate\Support\Str;

class DownloadController extends Controller
{
    
    /**
     * 
     * Download The File... The Request Includes The Enrypted Download File ID and The Recaptcha
     * @param Request
     * @return readfile()
     * 
     */
    public function downloadDownload(Request $request){

        // Validate the encrypted id and the recaptcha

        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
            'recaptcha' => 'required|string'
        ]);

        $ip = $_SERVER['REMOTE_ADDR'];
        $recaptcha = new Recaptcha($ip, $request->recaptcha);
        $recaptchaValidate = $recaptcha->validate();

        if($validator->fails() || !$recaptchaValidate){
            return back()->withErrors('Something went wrong! Please try again.');
        }

        $encrypt_id = $request->id;
        $id = decrypt($encrypt_id);
        $download = Download::findOrFail($id);
        $post = $download->post;
        $downloadEvent = new DownloadEvent();
        $downloadEvent->post()->associate($post);
        $downloadEvent->download()->associate($download);

        // If the user with this ip or user_id has downloaded the file before don't store the download event
        $checkPastDownloads = $post->downloadEvents()->where(function($query) use ($ip, $id) {
            $query->where('download_id', $id);
            if(Auth::check()){
                $query->where('ip_address', $ip)->orWhere('user_id', Auth::id());
            } else {
                $query->where('ip_address', $ip);
            }
        });
        if(empty($checkPastDownloads->first())){
            $downloadEvent = new DownloadEvent();
            $downloadEvent->ip_address = $ip;
            $downloadEvent->download_id = $id;
            $downloadEvent->post()->associate($post);
            if(Auth::check()){
                $downloadEvent->user()->associate(Auth::user());
            }
            $downloadEvent->save();
        }

        $post = $download->post;
        $path = $download->getPath();
        $mime = $download->getMime();
        $url = Storage::cloud()->temporaryUrl($download->url, now()->addSeconds(10));
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . Str::slug($post->title, '-') . '.' . $mime);
        header("Content-Type: " . $mime);
        return readfile($url);

    }


    /**
     * 
     * Download The Admin For Files.
     * @param int
     * @return readfile()
     * 
     */
    public function adminDownloadDownload($id){

        $download = Download::findOrFail($id);
        $post = $download->post;
        $path = $download->getPath();
        $mime = $download->getMime();
        $url = Storage::cloud()->temporaryUrl($download->url, now()->addSeconds(10));
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . Str::slug($post->title, '-') . '.' . $mime);
        header("Content-Type: " . $mime);
        return readfile($url);

    }


    /**
     * 
     * 
     * Delete Download For Admins
     * @param Integer id
     * @return RedirecResponse
     * 
     * 
     */
    public function adminDelete($id){

        $download = Download::findOrFail($id);
        $file = Storage::delete($download->url);
        $download->delete();
        return back()->with('status', 'A Download Has Been Deleted.');

    }



    /**
     * 
     * 
     * Add a Download File For an Post Only for Admins
     * @param Integer id 
     * @return Response
     * 
     * 
     */
    public function adminAdd(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'upload' => 'required|file|max:100000'
        ])->validate();
            
        $upload = $request->upload;

        $post = Post::findOrFail($id);
        if($post->downloads->count() < 5){

            $download = new Download();
            $name = $upload->getClientOriginalName();
            $extension = $upload->getClientOriginalExtension();
            $name = str_replace('.' . $extension, '', $name);
            $download->name = $name;
            $path = $upload->store('downloads', 's3');
            $download->url = $path;
            $post->downloads()->save($download);

            return back()->with('status', 'A Download Has Been Added Successfully.');
        }
        return back()->withErrors('Maximum Number of Downloads Has Been Reached.');
    }


}
