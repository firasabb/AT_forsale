<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Auth;
use \App\UserLink;
use Validator;
use Storage;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    

    public function __construct(){

        $this->middleware('role:user');

    }



    public function showProfile($username){

        $user = User::where('username', $username)->firstOrFail();
        $logged_user = Auth::user();
        $isUser = false;
        if($logged_user->id == $user->id || $logged_user->hasRole('admin')){
            $isUser = true;
        }
        return view('users.profile', ['user' => $user, 'isUser' => $isUser]);

    }


    public function setupProfilePage($username){

        $logged_user = Auth::user();
        $user = User::where('username', $username)->firstOrFail();

        $instagram = $user->userLinks()->where('name', 'instagram')->first();
        $facebook = $user->userLinks()->where('name', 'facebook')->first();
        $github = $user->userLinks()->where('name', 'github')->first();
        $youtube = $user->userLinks()->where('name', 'youtube')->first();
        $website = $user->userLinks()->where('name', 'website')->first();
        $portfolio = $user->userLinks()->where('name', 'portfolio')->first();

        if($logged_user->id == $user->id || $logged_user->hasRole('admin')){
            return view('users.profileSetup', ['user' => $user, 'instagram' => $instagram, 'facebook' => $facebook,
            'github' => $github, 'youtube' => $youtube, 'website' => $website, 'portfolio' => $portfolio]);
        }

        abort(403, 'Unauthorized action.');

    }


    public function setupProfileRequest($username, Request $request){

        $logged_user = Auth::user();
        $user = User::where('username', $username)->with('userLinks')->firstOrFail();

        if($logged_user->id == $user->id || $logged_user->hasRole('admin')){

            $validator = Validator::make($request->all(), [
                'name' => 'string|max:50|nullable',
                'profile_picture' => 'file|max:2000|mimes:png,jpeg|nullable',
                'bio' => 'string|max:1000|nullable',
                'email' => ['email', 'nullable', Rule::unique('users', 'email')->ignore($user->email, 'email')],
                'paypal' => ['email', 'nullable', Rule::unique('users', 'paypal')->ignore($user->paypal, 'paypal')],
                'instagram_link' => 'string|max:50|nullable',
                'facebook_link' => 'string|max:50|nullable',
                'github_link' => 'string|max:100|nullable',
                'youtube_link' => 'string|max:100|nullable',
                'website_link' => 'url|max:100|nullable',
                'portfolio_link' => 'url|max:100|nullable'
            ]);


            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }

            $profilePicture = $request->profile_picture;
            if($profilePicture){
                if(!Arr::has(Storage::cloud()->directories(), 'profiles')){
                    Storage::cloud()->makeDirectory('profiles');
                }
                $unique = uniqid();
                $path = $profilePicture->storePublicly('profiles/' . $unique, 's3');
                $user->avatar_url = $path;
            }
            $user->name = $request->name;
            $user->bio = $request->bio;
            $user->email = $request->email;
            $user->paypal = $request->paypal;
            $user->save();

            $links = ['instagram' => $request->instagram_link, 'facebook' => $request->facebook_link,
             'github' => $request->github_link, 'youtube' => $request->youtube_link, 'website' => $request->website_link, 'portfolio' => $request->portfolio_link];

            $this->saveUserLinks($links, $user);

            return back()->with('status', 'Your information has been updated');
        }

        return abort(403, 'Unauthorized action.');

    }

    /**
     * 
     * Save or Update User Links For A User
     * @param Array links from request
     * @param Object the user
     * 
     */

    private function saveUserLinks($links, $user){

        foreach($links as $key => $value){
            $check = $user->userLinks()->where('name', $key)->first();
            $checkLink = $this->validateUserLink($key, $value);
            if(!$checkLink){
                return back()->withErrors('Please add the right link');
            }
            if(empty($check) && !empty($value)){
                $userLink = new UserLink();
                $userLink->name = $key;
                $userLink->url = $value;
                $userLink->user()->associate($user);
                $userLink->save();
                
            } else if($check['url'] != $value && !empty($check)){
                $userLink = UserLink::where('name', $key)->first();;
                $userLink->url = $value;
                $userLink->save();
            }
        }
    }


    /**
     * 
     * Validate if the links to their platforms
     * @param String Key which is the name of the platform
     * @param String Value which is the posted link
     * 
     */

    private function validateUserLink($key, $value){

        switch($key){
            case 'instagram':
                $check = $this->validateFacebookInstagram($value);
                if(!$check){
                    return false;
                }
                break; 
            case 'facebook':
                $check = $this->validateFacebookInstagram($value);
                if(!$check){
                    return false;
                }
                break;
            case 'github':
                $check = $this->validatePlatformLink('github', $value);
                if(!$check){
                    return false;
                }
                break;
            case 'youtube':
                $check = $this->validatePlatformLink('youtube', $value);
                if(!$check){
                    return false;
                }
                break;
            case 'portfolio':
                break;
            case 'website':
                break;
        }

        return true;
    }



    private function validatePlatformLink($platform, $link){

        $platform = '/^(https?:\/\/)?(www\.)?' . $platform . '.com\/[a-zA-Z0-9(\.\?)?]/';
        if(preg_match($platform, $link) == 1) {
            return true;
        } else if(empty($link)){
            return true;
        } else {
            return false;
        }
    
    }


    private function validateFacebookInstagram($link){

        if(preg_match('/(https?:\/\/)?([\w\.]*)facebook\.com\/([a-zA-Z0-9_]*)$/', $link) !== false){
            return true;
        } else if(preg_match('/(https?:\/\/)?([\w\.]*)instagram\.com\/([a-zA-Z0-9_]*)$/', $link) !== false){
            return true;
        }
        else if (filter_var($link, FILTER_VALIDATE_URL) === false){
            return true;
        }if(empty($link)){
            return true;
        }
        return false;
    }




    public function changePasswordPage($username){

        $user = User::where('username', $username)->firstOrFail();
        $logged_user = Auth::user();

        if($user->id == $logged_user->id || $logged_user->hasRole('admin')){

            return view('users.changePassword', ['user' => $user]);

        } else {

            return abort(403, 'Unauthorized action.');

        }

    }


    public function changePasswordRequest(Request $request, $username){

        $user = User::where('username', $username)->firstOrFail();
        $logged_user = Auth::user();

        if($user->id == $logged_user->id || $logged_user->hasRole('admin')){

            $validator = Validator::make($request->all(), [
                'old_password' => 'required|string|min:8',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            if($validator->fails()){
                return back()->withErrors($validator);
            }

            $old_password = $request->old_password;
            $new_password = $request->new_password;

            if (Hash::check($old_password, $user->password)) {
                if (!Hash::check($new_password, $user->password)) {
                    $user->fill(['password' => Hash::make($new_password)])->save();
                    Auth::logout();
                    return redirect('/login');
                } else {
                    return back()->withErrors('New password cannot be the same old one.');
                }
            }
            return back()->withErrors('Your old password is wrong.');

        } else {

            return abort(403, 'Unauthorized action.');

        }

        

    }

}
