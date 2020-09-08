<?php

/**
 * 
 * This Service is To Process The Download Data
 * For Example Showing The Size of The Image
 * 
 */


namespace App\Services;

class DownloadService{


    public function sizeFormat(int $size){
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
        $bytes = max($size, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow]; 
    }

    public function getImageSize($url){

        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $isImage = false;
        $imgArr = ['jpg', 'jpeg', 'png', 'bmp'];
        foreach($imgArr as $img){
            if(strpos($ext, $img) !== FALSE){
                $isImage = true;
            }
        }
        
        if($isImage === true){
            $size = getimagesize($url);
            if(!empty($size)){
                return $size[0] . ' x ' . $size[1];
            }
        }
        
        return '';
    }

}



?>