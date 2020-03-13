<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Download;
use App\Art;
use Storage;
use Validator;

class DownloadController extends Controller
{
    

    public function __construct(){

    }


    public function downloadDownload($id, Request $request){

        $id = $request->id;
        $download = Download::findOrFail($id);
        $art = $download->art;
        $path = $download->getPath();
        $mime = $download->getMime();
        $url = Storage::cloud()->temporaryUrl($download->url, now()->addSeconds(10));
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . $art->title);
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
     * Add a Download File For an Art Only for Admins and Moderators
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

        $art = Art::findOrFail($id);
        if($art->downloads->count() < 5){

            $download = new Download();
            $name = $upload->getClientOriginalName();
            $extension = $upload->getClientOriginalExtension();
            $name = str_replace('.' . $extension, '', $name);
            $download->name = $name;
            $path = $upload->store('downloads', 's3');
            $download->url = $path;
            $art->downloads()->save($download);

            return back()->with('status', 'A Download Has Been Added Successfully.');
        }
        return back()->withErrors('Maximum Number of Downloads Has Been Reached.');
    }


}
