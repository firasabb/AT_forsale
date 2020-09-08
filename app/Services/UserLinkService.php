<?php


/**
 * 
 * This Service is to Parse The User Links, And To Add Colors And Icons
 * 
 */


namespace App\Services;

use App\UserLink;

class UserLinkService{


    // Convert 'username' or '@username' to a Facebook link 
    public function userLinkParser(String $username, String $platform){
        if(($platform == 'facebook' || $platform == 'instagram') && $username){
            if($username[0] == '@'){
                $username = str_replace('@', '', $username, 1);
            }
            $username = 'https://www.' . $platform . '.com/' . $username;
        }
        return $username;
    }


    /**
     * 
     * Generate a color for each platform
     * @param String platform
     * @return String
     * 
     */
    public function userLinkColor(String $platform){
        
        $color = '';

        switch($platform){
            case 'facebook':
                $color = '#5393e6';
                break;
                
            case 'instagram':
                $color = '#ff71e2';
                break;
        
            case 'github':
                $color = '#656768';
                break;
                
            case 'youtube':
                $color = '#ff5a5a';
                break;

            case 'portfolio':
                $color = '#33d9f4';
                break;
            default:
                $color = '#ccc';
                break;
        }
        return $color;

    }


    /**
     * 
     * Generate the icon link for button
     * @param String platform
     * @param String user link url
     * @return String link
     * 
     */
    public function userLinkIcon($platform, $url){

        $icon = '';
        $color = '';

        switch($platform){
            case 'facebook':
                $icon = 'fa fa-facebook';

                break;
                
            case 'instagram':
                $icon = 'fa fa-instagram';
                break;
        
            case 'github':
                $icon = 'fa fa-github';
                break;
                
            case 'youtube':
                $icon = 'fa fa-youtube';
                break;

            case 'portfolio':
                $icon = 'fa fa-behance';
                break;
            default:
                $icon = 'fa fa-link';
                break;
        }

        $color = $this->userLinkColor($platform);
        $url = htmlspecialchars($url);
        $url = $this->userLinkParser($url, $platform);
        $link = "<a class='btn btn-block white-text py-point-8 {$icon}' target='_blank' href='{$url}' style='background-color: {$color};'></a>";
        return $link;
    }

}