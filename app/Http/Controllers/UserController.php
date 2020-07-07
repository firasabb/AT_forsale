<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Auth;
use \App\UserLink;
use \App\Media;
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
        $activeAssets = $user->activeAssets;
        return view('users.profile', ['user' => $user, 'activeAssets' => $activeAssets]);

    }


    public function showMyProfile(){

        $user = Auth::user();
        $activeAssets = $user->activeAssets;
        return view('users.myProfile', ['user' => $user, 'activeAssets' => $activeAssets]);

    }


    public function setupProfilePage(){

        $user = Auth::user();

        $instagram = $user->userLinks()->where('name', 'instagram')->first();
        $facebook = $user->userLinks()->where('name', 'facebook')->first();
        $github = $user->userLinks()->where('name', 'github')->first();
        $youtube = $user->userLinks()->where('name', 'youtube')->first();
        $website = $user->userLinks()->where('name', 'website')->first();
        $portfolio = $user->userLinks()->where('name', 'portfolio')->first();

        return view('users.profileSetup', ['user' => $user, 'instagram' => $instagram, 'facebook' => $facebook,
        'github' => $github, 'youtube' => $youtube, 'website' => $website, 'portfolio' => $portfolio]);

    }


    public function setupProfileRequest(Request $request){

        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:50|nullable',
            'profile_picture' => 'image|max:1000|nullable|clamav',
            'bio' => 'string|max:1000|nullable',
            'email' => ['email', 'nullable', Rule::unique('users', 'email')->ignore($user->email, 'email')],
            'paypal' => ['email', 'nullable', Rule::unique('users', 'paypal')->ignore($user->paypal, 'paypal')],
            'instagram_link' => 'string|max:50|nullable',
            'facebook_link' => 'string|max:50|nullable',
            'github_link' => 'string|max:100|nullable',
            'youtube_link' => 'string|max:100|nullable',
            'website_link' => 'active_url|max:100|nullable',
            'portfolio_link' => 'active_url|max:100|nullable'
        ]);


        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $profilePicture = $request->profile_picture;
        if($profilePicture){
            if(!Arr::has(Storage::cloud()->directories(), 'profiles')){
                Storage::cloud()->makeDirectory('profiles');
            }
            if(!empty($user->avatar())){
                $user->avatar()->delete();
            }
            $media = new Media();
            $media->sorting = 4;
            $path = Storage::cloud()->putFile('profiles', $profilePicture, 'public');
            $media->url = $path;
            $media->public_url = Storage::cloud()->url($path);
            $media->save();
            $user->medias()->attach($media);
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->bio = $request->bio;
        $user->email = $request->email;
        $user->paypal = $request->paypal;
        $user->save();

        if($request->facebook){
            $request->facebook = $this->facebookInstagramLinks($request->facebook);
        }
        
        if($request->instagram){
            $request->instagram = $this->facebookInstagramLinks($request->instagram);
        }

        $links = ['instagram' => $request->instagram_link, 'facebook' => $request->facebook_link,
            'github' => $request->github_link, 'youtube' => $request->youtube_link, 'website' => $request->website_link, 'portfolio' => $request->portfolio_link];

        $this->saveUserLinks($links, $user);

        return back()->with('status', 'Your information has been updated');

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
                return back()->withErrors('Please add a valid link');
            }
            if(empty($check) && !empty($value)){
                $userLink = new UserLink();
                $userLink->name = $key;
                $userLink->url = $value;
                $userLink->user()->associate($user);
                $userLink->save();
                
            } else if(empty($value) && !empty($check)){
                $check->delete();
            } else if($check){
                if($check->url != $value && !empty($check)){
                    $check->url = $value;
                    $check->save();
                }
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
                break; 
            case 'facebook':
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
                $check = $this->validatePlatformLink('behance', $value);
                if(!$check){
                    return false;
                }
                break;
            case 'website':
                break;
        }

        return true;
    }



    private function validatePlatformLink($platform, $link){

        $platform = '/^(https?:\/\/)?(www\.)?' . $platform . '.(com|net)?\/[a-zA-Z0-9(\.\?)?]/';
        if(preg_match($platform, $link) == 1) {
            return true;
        } else if(empty($link)){
            return true;
        } else {
            return false;
        }
    
    }

    private function facebookInstagramLinks($link){

        // We don't want a url
        // it is accepted as "username" or "@username"
        if(filter_var($link, FILTER_VALIDATE_URL) !== false){
            return '';
        }
        if($link[0] == '@'){
            $link = str_replace('@', '', $link, 1);
        }

        return $link;
    }
    

    public function changePasswordPage(){

        $user = Auth::user();
        return view('users.changePassword', ['user' => $user]);

    }


    public function changePasswordRequest(Request $request){

        $user = $request->user();
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

    }

    /**
     * 
     * Go to the user's dashboard
     * @return Response
     * 
     */
    public function dashboard(){
        $user = Auth::user();
        return view('users.dashboard', ['user', $user]);
    } 


    /**
     * Show assets in the user's dashboard
     * @return Response
     */
    public function myAssetsPage(){

        $user = Auth::user();
        $assets = $user->assets()->paginate(10);
        return view('users.myAssets', ['assets' => $assets]);
    }


    /**
     * Show the user's ad in the user's dashboard
     * @return Response
     */
    public function userAd(){

        $user = Auth::user();
        $userLinks = $user->userLinks;
        $ad = $user->userAds()->first();
        $content = '';
        if(!empty($ad)){
            $content = unserialize($ad->content);
        }
        return view('users.userAd', ['user' => $user, 'ad' => $ad, 'content' => $content, 'userLinks' => $userLinks]);
    }


    /**
     * 
     * Send Verification Email
     * @return Response
     * 
     */
    public function sendVerificationEmail(){
        $user = Auth::user();
        $user->sendEmailVerificationNotification();
        return back()->with('status', 'Verification Email Has Been Sent');
    }

}
