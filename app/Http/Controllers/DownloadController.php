<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Download;
use App\DownloadEvent;
use App\Asset;
use Auth;
use Storage;
use Validator;
use Carbon\Carbon;

class DownloadController extends Controller
{
    

    public function __construct(){

    }


    public function downloadDownload($id, Request $request){

        $ip = $request->ip();
        $checkPastDownloads = DownloadEvent::whereDate('created_at', Carbon::today())->count();

        if($checkPastDownloads > 2 && !Auth::check()){
            return redirect()->route('login');
        }

        $encrypt_id = $request->id;
        $id = decrypt($encrypt_id);
        $download = Download::findOrFail($id);
        $asset = $download->asset;
        $downloadEvent = new DownloadEvent();
        $downloadEvent->asset()->associate($asset);
        $downloadEvent->download()->associate($download);
        if(Auth::check()){
            $user = Auth::user();
            $downloadEvent->user()->associate($user);
        }
        $downloadEvent->ip_address = $ip;
        $downloadEvent->save();
        $asset = $download->asset;
        $path = $download->getPath();
        $mime = $download->getMime();
        $url = Storage::cloud()->temporaryUrl($download->url, now()->addSeconds(10));
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $asset->title);
        header("Content-Type: " . $mime);
        return readfile($url);

    }


    /**
     * 
     * 
     * Delete Download For Admins And Moderators Only
     * @param Integer id
     * @return Response
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
     * Add a Download File For an Asset Only for Admins and Moderators
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

        $asset = Asset::findOrFail($id);
        if($asset->downloads->count() < 5){

            $download = new Download();
            $name = $upload->getClientOriginalName();
            $extension = $upload->getClientOriginalExtension();
            $name = str_replace('.' . $extension, '', $name);
            $download->name = $name;
            $path = $upload->store('downloads', 's3');
            $download->url = $path;
            $asset->downloads()->save($download);

            return back()->with('status', 'A Download Has Been Added Successfully.');
        }
        return back()->withErrors('Maximum Number of Downloads Has Been Reached.');
    }


}
