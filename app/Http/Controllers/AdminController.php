<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailTo;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){

        $this->middleware('role:admin');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){

        return view('admin.dashboard');
    }


    /********* 
     * 
     * 
     * 
     *      Users
     * 
     * 
     * 
     * 
    */

    public function indexUsers($users = null){

        if(!$users){
            $users = User::orderBy('id', 'DESC')->paginate(20);
        } else {
            $users = $users->paginate(20);
        }
        $roles = Role::all();
        return view('admin.users.users')->with('users', $users)->with('roles', $roles);

    }

    
    public function showUser($id){

        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.show', ['user' => $user, 'roles' => $roles]);

    }


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


    public function destroyUser($id){

        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/admin/dashboard/users/')->with('status', 'User has been deleted!');

    }


    public function generatePassword($id){
        
        $user = User::findOrFail($id);
        $generatedPassword = $this->generateString();
        $user->password = bcrypt($generatedPassword);
        $user->save();

        return redirect('/admin/dashboard/user/' . $user->id)->with('status', 'User password has been changed to:  ' . $generatedPassword);

    }



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
        return $this->indexUsers($users);
    }

    private function generateString($ln = 10){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $generatedString = '';
        for($i = 0; $i < $ln; $i++){
            $generatedString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $generatedString;
    }

    /********
     * 
     * 
     * 
     * 
     * Roles
     * 
     * 
     * 
     * 
     */


     public function indexRoles(){

        $roles = Role::orderBy('id', 'desc')->paginate(20);
        $permissions = Permission::all();
        return view('admin.roles.roles', ['roles' => $roles, 'permissions' => $permissions]);

     }


     
     public function addRole(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'array'
        ]);

        if($validator->fails()){
            return redirect('/admin/dashboard/roles/')->withErrors($validator)->withInput();
        }

        $role = new Role();
        $role->name = strtolower($request->name);
        $role->save();

        $permissions = $request->permissions;

        $role->givePermissionTo($permissions);    

        return redirect('/admin/dashboard/roles/')->with('status', 'Role has been added successfully!');

    }


     public function showRole($id){

        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('admin.roles.show', ['role' => $role, 'permissions' => $permissions]);

     }


     public function editRole(Request $request, $id){

        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permissions' => 'array'
        ]);

        if($validator->fails()){
            return redirect('/admin/dashboard/role/' . $role->id)->withErrors($validator)->withInput();
        }

        $role->name = strtolower($request->name);
        $role->save();

        $permissions = $request->permissions;

        $role->syncPermissions($permissions);

        return redirect('/admin/dashboard/role/' . $role->id)->with('status', 'Role has been edited successfully!');

     }


     public function destroyRole($id){

        $role = Role::findOrFail($id);

        $role->delete();

        return redirect('/admin/dashboard/roles/')->with('status', 'Role has been deleted successfully!');

     }




    /*********
     * 
     * 
     * 
     * 
     * Permissions
     * 
     * 
     * 
     * 
     *  */ 
    



     public function indexPermissions(){

        $permissions = Permission::orderBy('id', 'desc')->paginate(20);
        $roles = Role::all();
        return view('admin.permissions.permissions', ['permissions' => $permissions, 'roles' => $roles]);

     }


     public function addPermission(Request $request){


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'roles' => 'array',
        ]);

        if($validator->fails()){

            return redirect('admin/dashboard/permissions')->withErrors($validator)->withInput();

        }

        $permission = new Permission();
        $permission->name = strToLower($request->name);
        $permission->save();

        $roles = $request->roles;

        $permission->assignRole($roles);

        return redirect('/admin/dashboard/permissions/')->with('status', 'Permission has been added successfully!');

     }


     public function showPermission($id){

        $permission = Permission::findOrFail($id);
        $roles = Role::all();

        return view('admin.permissions.show', ['permission' => $permission, 'roles' => $roles]);


     }


     public function editPermission(Request $request, $id){

        $permission = Permission::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'roles' => 'array'
        ]);

        if($validator->fails()){
            return redirect('/admin/dashboard/permission/' . $permission->id)->withErrors($validator)->withInput();
        }

        $permission->name = strToLower($request->name);
        $permission->save();

        $roles = $request->roles;

        $permission->syncRoles($roles);

        return redirect('/admin/dashboard/permission/' . $permission->id)->with('status', 'Permission has been edited successfully!');


     }


     public function destroyPermission($id){

        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect('/admin/dashboard/permissions/')->with('status', 'Permission has been deleted!');

     }



     /**
      * Show the form to send custom emails
      * @return Response
      *
      */
     public function sendEmailForm(){

        return view('admin.emails.send');

     }



     /**
      * Send custom emails to people
      * @param Request $request
      * @return Response
      *
      */
     public function sendEmail(Request $request){
        
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
            'reciever' => 'email',
            'subject' => 'required|string'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator);
        }

        $body = $request->body;
        $reciever = $request->reciever;
        $subject = $request->subject;

        Mail::to($reciever)->bcc('firas.abb.101@gmail.com')->send(new EmailTo($body, $subject));

        return back()->with('status', 'The email has been sent!');

     }

}
