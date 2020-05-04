<?php

namespace App\Services;

class Recaptcha{


    private $ip;
    private $recaptcha;


    /**
     * 
     * Constructor
     * 
     */
    public function __construct(?String $ip, ?String $recaptcha){
        $this->ip = $ip;
        $this->recaptcha = $recaptcha;
    }

    /**
     * 
     * Google Recaptcha Validation
     * @param String ip
     * @param String recaptcha string
     * @return Boolean
     * 
     */
    public function validate(){

        $recaptcha = $this->recaptcha;
        $ip = $this->ip;

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
                'secret' => config('services.recaptcha.secret'),
                'response' => $recaptcha,
                'remoteip' => $ip
            ];
        $options = [
                'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
                ]
            ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);
        if (!$resultJson->success) {
            return false;
        }
        if ($resultJson->score >= 0.4) {
            return true;
        } else {
            return false;
        }

    }

}