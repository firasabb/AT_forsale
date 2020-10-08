<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Auth;
use \App\UserLink;
use \App\Media;
use Validator;
use Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{





    /********* 
     * 
     * Admin User Management
     * 
    *********/

    /**
     * 
     * Index The Users With Filters For Admins
     * @param string $order
     * @param bool $desc
     * @param array or null Users
     * @return View
     * 
     */
    public function indexUsers($order = '', $desc = false, $users = null){

        
        // Order By Options For Filtering
        // To show all table columns use the line below and comment the second line
        //$orderByOptions = DB::getSchemaBuilder()->getColumnListing($usersTable);
        $orderByOptions = ['id', 'username', 'status', 'created_at'];

        $defaultOrder = 'id';

        if(!$users){
            // Check if the filter is valid
            if($order){
                if(in_array($order, $orderByOptions) === TRUE){
                    $defaultOrder = $order;
                }
            }
            if($desc){
                $users = User::orderBy($defaultOrder, 'desc');
            }
            if(!$desc){
                $users = User::orderBy($defaultOrder, 'asc');
            }
        }

        $users = $users->paginate(20);

        $user = new User();
        $usersTable = $user->getTable();

        // Get all the user's statuses
        $statuses = DB::table($usersTable)->select('status')->distinct()->pluck('status');

        $roles = Role::all();
        return view('admin.users.users', ['users' => $users, 'statuses' => $statuses, 'roles' => $roles, 'order' => $order, 'desc' => $desc]);

    }

    
    /**
     * 
     * Show The User's Info For Admins 
     * @param int user id
     * @return View
     * 
     */
    public function showUser($id){

        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.show', ['user' => $user, 'roles' => $roles]);

    }


    /**
     * 
     * Add a User Admins 
     * @param Request $request
     * @return RedirectResponse
     * 
     */
    public function addUser(Request $request){

        $validator = Validator::make($request->all(), [
            'first_name' => 'min:3|max:30|required',
            'last_name' => 'min:3|max:30|required',
            'username' => 'required|unique:users',
            'email' => 'email|required',
            'password' => 'min:6|max:50|required',
            'roles' => 'array|required'
        ]);

        if ($validator->fails()) {
            return redirect('/admin/dashboard/users/')->withErrors($validator)->withInput(Input::except('password'));
        }

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();

        $roles = $request->roles;

        $user->assignRole($roles);

        return redirect('/admin/dashboard/users/')->with('status', 'User has been added successfully!');

    }


    /**
     * 
     * Edit The User's Info For Admins 
     * @param int user id
     * @param Request $request
     * @return RedirectResponse
     * 
     */
    public function editUser($id, Request $request){

        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'min:3|max:30',
            'last_name' => 'min:3|max:30',
            'email' => 'email',
            'roles' => 'array'
        ]);

        if($validator->fails()){
            return redirect('/admin/dashboard/user/' . $user->id)->withErrors($validator)->withInput(Input::except('password'));
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->save();

        $roles = $request->roles;

        $user->syncRoles($roles);


        return redirect('/admin/dashboard/user/' . $user->id)->with('status', 'User has been edited successfully!');

    }

    
    /**
     * 
     * Delete The User For Admins 
     * Please note that soft delete is activated
     * @param int user id
     * @return RedirectResponse
     * 
     */
    public function destroyUser($id){

        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/admin/dashboard/users/')->with('status', 'User has been deleted!');

    }


    /**
     * 
     * Generate a Password For The User For Admins 
     * @param int user id
     * @return RedirectResponse
     * 
     */
    public function generatePassword($id){
        
        $user = User::findOrFail($id);
        $generatedPassword = $this->generateString();
        $user->password = bcrypt($generatedPassword);
        $user->save();

        return redirect('/admin/dashboard/user/' . $user->id)->with('status', 'User password has been changed to:  ' . $generatedPassword);

    }



    /**
     * 
     * Search Users For Admins  
     * @param Request $request
     * @return indexusers($users)
     * 
     */
    public function searchUsers(Request $request){
        
        $users = array();

        $validator = Validator::make($request->all(), [
            'email' => 'email|nullable',
            'id' => 'integer|nullable',
            'first_name' => 'string|max:50|nullable',
            'last_name' => 'string|max:50|nullable',
            'username' => 'string|max:100|nullable'
        ]);
            if($validator->fails() || empty($request->all())){
                return redirect('/admin/dashboard/users/')->withErrors($validator)->withInput();
            }
        $email = $request->email;
        $id = $request->id;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $username = $request->username;

        $where_arr = array();

        if($email){

            $email_where = ['email', 'LIKE', $email];
            array_push($where_arr, $email_where);

        } if ($id){

            $id_where = ['id', '=', $id];
            array_push($where_arr, $id_where);

        } if($first_name){

            $name_where = ['first_name', 'LIKE', '%' . $first_name . ' ' . $last_name . '%'];
            array_push($where_arr, $name_where);

        } if($last_name){

            $name_where = ['last_name', 'LIKE', '%' . $last_name . '%'];
            array_push($where_arr, $name_where);

        } if($username){

            $username_where = ['username', 'LIKE', '%' . $username . '%'];
            array_push($where_arr, $username_where);

        }

        $users = User::where($where_arr);

        if(empty($users)){
            return $this->indexUsers();
        }
        return $this->indexUsers('', '', $users);
    }


    /**
     * 
     * Filter Users From Ajax Request
     * NOT USED
     * FOR FUTURE UPDATES
     * 
     */
    public function filterUsers(Request $request){

        $request->validate([
            'email' => 'email|nullable',
            'first_name' => 'string|max:50|nullable',
            'last_name' => 'string|max:50|nullable',
            'username' => 'string|max:50|nullable',
            'status' => 'string|nullable',
            'order' => 'string|nullable',
            'id' => 'int|nullable'
        ]);

        $email = $request->email;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $username = $request->username;
        $id = $request->id;
        $status = $request->status;
        $order = $request->order;

        $users = new User();

        $whereArray = array();

        if($email){
            $whereArray[] = ['email', 'LIKE' ,$email];
        }
        if($first_name){
            $whereArray[] = ['first_name', 'LIKE' ,$first_name];
        }
        if($last_name){
            $whereArray[] = ['last_name', 'LIKE' ,$last_name];
        }
        if($username){
            $whereArray[] = ['username', 'LIKE' ,$username];
        }
        if($status){
            $whereArray[] = ['status', $status];
        }
        if($id){
            $whereArray[] = ['id' ,$id];
        }

        if(!empty($whereArray)){
            $users = $users->where($whereArray);
        }

        if($order){
            $users = $users->orderBy($order, 'DESC');
        }

        $this->adminIndex($order, true, $users);


    }


    /**
     * 
     * Generate a random password
     * @param int $ln password length
     * @return string
     * 
     */
    private function generateString($ln = 10){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $generatedString = '';
        for($i = 0; $i < $ln; $i++){
            $generatedString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $generatedString;
    }



    /******** End Admin User Management */

    /**
     * 
     * Show a profile of a user
     * @param string username
     * @return View
     * 
     */
    public function showProfile($username){

        $user = User::where('username', $username)->firstOrFail();
        $approvedPosts = $user->approvedPosts;
        $categories = DB::table('posts')->select('categories.*')->distinct()->where([['user_id', $user->id], ['status', 'published']])->join('categories', 'posts.category_id', '=', 'categories.id')->get();
        $dashboard = false;
        return view('users.profile', ['user' => $user, 'approvedPosts' => $approvedPosts, 'categories' => $categories, 'dashboard' => $dashboard]);

    }


    /**
     * 
     * Show the authenticated user's profile
     * @return View
     * 
     */
    public function showMyProfile(){

        $user = Auth::user();
        $approvedPosts = $user->approvedPosts;
        $categories = DB::table('posts')->select('categories.*')->distinct()->where([['user_id', $user->id], ['status', 'published']])->join('categories', 'posts.category_id', '=', 'categories.id')->get();
        $dashboard = true;
        return view('users.profile', ['user' => $user, 'approvedPosts' => $approvedPosts, 'categories' => $categories, 'dashboard' => $dashboard]);

    }


    /**
     * 
     * Show the authenticated user's setup profile page
     * @return View
     * 
     */
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


    /**
     * 
     * Show the authenticated user's setup profile page
     * @param Request $request
     * @return RedirectResponse
     * 
     */
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
            if(!Arr::has(Storage::directories(), 'profiles')){
                Storage::makeDirectory('profiles');
            }
            if(!empty($user->avatar())){
                $user->avatar()->delete();
            }
            $media = new Media();
            $media->sorting = 4;
            $path = Storage::putFile('profiles', $profilePicture, 'public');
            $media->url = $path;
            $media->public_url = Storage::url($path);
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


    /**
     * 
     * Validate that the website is valid
     * @param string platform
     * @return bool
     * 
     */
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

    /**
     * 
     * Correct Facebook and Instagram links
     * @param string $link
     * @return string
     * 
     */
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
    

    /**
     * 
     * Show the authenticated user's change password page
     * @return View
     * 
     */
    public function changePasswordPage(){

        $user = Auth::user();
        return view('users.changePassword', ['user' => $user]);

    }


    /**
     * 
     * Change user's password
     * @param Request $request
     * @return RedirectResponse
     * 
     */
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
                return redirect()->route('login');
            } else {
                return back()->withErrors('New password cannot be the same old one.');
            }
        }
        return back()->withErrors('Your old password is wrong.');

    }

    /**
     * 
     * Go to the user's dashboard
     * @return View
     * 
     */
    public function dashboard(){
        $user = Auth::user();
        return view('users.dashboard', ['user', $user]);
    } 


    /**
     * Show posts in the user's dashboard
     * @return View
     */
    public function myPostsPage(){

        $user = Auth::user();
        $posts = $user->posts()->orderBy('id', 'desc')->paginate(10);
        return view('users.myPosts', ['posts' => $posts]);
    }



    /**
     * 
     * Send Verification Email
     * @return RedirectResponse
     * 
     */
    public function sendVerificationEmail(){
        $user = Auth::user();
        $user->sendEmailVerificationNotification();
        return back()->with('status', 'Verification Email Has Been Sent');
    }

}
