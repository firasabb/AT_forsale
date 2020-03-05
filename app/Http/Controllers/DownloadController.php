<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Download;
use Storage;

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

}
