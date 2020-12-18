<?php

namespace App\Http\Controllers;

use App\ContactMessage;
use Illuminate\Http\Request;
use Validator;
use App\Services\Recaptcha;

class ContactMessageController extends Controller
{

    /**
     * Show The Form for Creating a New Contact Message.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('screens.contact');
    }


    /**
     * Store The Contact Message.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recaptcha' => 'required|string',
            'title' => 'required|string|max:150',
            'first_name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'email' => 'required|email',
            'body' => 'required|string|max:5000'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $ip = $_SERVER['REMOTE_ADDR'];
        $recaptcha = new Recaptcha($ip, $request->recaptcha);
        $recaptchaValidate = $recaptcha->validate();

        if(!$recaptchaValidate){
            return back()->withErrors('Something went wrong with ReCAPTCHA, please try again');
        }

        $contactMessage = new ContactMessage();
        $contactMessage->title = $request->title;
        $contactMessage->sender_name = $request->first_name . ' ' . $request->last_name;
        $contactMessage->sender_email = $request->email;
        $contactMessage->sender_ip = $ip;
        $contactMessage->body = $request->body;
        $contactMessage->save();

        return back()->with('status', 'Thank you for contacting us! Our team is going to help you with your inquiry as soon as possible.');
    }

}
