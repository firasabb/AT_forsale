<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use \App\Post;
use \App\Report;
use \App\Comment;
use \App\ContactMessage;
use \App\DownloadEvent;
use \App\ViewEvent;
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

        $activeUsers = new User();
        $activeUsers = $activeUsers->activeUsers()->count();
        $posts = new Post();
        $publishedPosts = $posts->publishedPosts()->count();
        $pendingPosts = $posts->where('status', 'pending')->count();
        $reports = Report::all()->count();
        $comments = Comment::all()->count();
        $contactMessages = ContactMessage::all()->count();
        $downloads = DownloadEvent::all()->count();
        $views = ViewEvent::all()->count();
        return view('admin.dashboard', ['activeUsers' => $activeUsers,
                                        'publishedPosts' => $publishedPosts,
                                        'pendingPosts'  => $pendingPosts,
                                        'reports' => $reports,
                                        'comments' => $comments,
                                        'contactMessages' => $contactMessages,
                                        'downloads' => $downloads,
                                        'views' => $views
                                        ]);
    }


    /********
     * 
     * 
     * Roles
     * 
     * 
     ********/


     /**
     * 
     * Index The Roles For Admins  
     * @return View
     * 
     */
     public function indexRoles(){

        $roles = Role::orderBy('id', 'desc')->paginate(20);
        $permissions = Permission::all();
        return view('admin.roles.roles', ['roles' => $roles, 'permissions' => $permissions]);

     }


    /**
     * 
     * Add a Role For Admins  
     * @param Request $request
     * @return View
     * 
     */
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


    /**
     * 
     * Show The Role Info For Admins  
     * @param int role id
     * @return View
     * 
     */
     public function showRole($id){

        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('admin.roles.show', ['role' => $role, 'permissions' => $permissions]);

     }


     /**
     * 
     * Edit The Role Info For Admins  
     * @param Request $request
     * @param Request role id
     * @return RedirectResponse
     * 
     */
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


     /**
     * 
     * Destroy a Role For Admins  
     * @param int $id
     * @return RedirectResponse
     * 
     */
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
     *********/ 
    


     /**
     * 
     * Index Permissions For Admins  
     * @return View
     * 
     */
     public function indexPermissions(){

        $permissions = Permission::orderBy('id', 'desc')->paginate(20);
        $roles = Role::all();
        return view('admin.permissions.permissions', ['permissions' => $permissions, 'roles' => $roles]);

     }


     /**
     * 
     * Add a Permissions For Admins  
     * @param Request $request
     * @return View
     * 
     */
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


     /**
     * 
     * Show Permission Info For Admins  
     * @param int permission id
     * @return View
     * 
     */
     public function showPermission($id){

        $permission = Permission::findOrFail($id);
        $roles = Role::all();

        return view('admin.permissions.show', ['permission' => $permission, 'roles' => $roles]);


     }


     /**
     * 
     * Show Permission Info For Admins  
     * @param Request $request
     * @param int permission id
     * @return RedirectResponse
     * 
     */
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



     /**
     * 
     * Show Permission Info For Admins  
     * @param int permission id
     * @return RedirectResponse
     * 
     */
     public function destroyPermission($id){

        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect('/admin/dashboard/permissions/')->with('status', 'Permission has been deleted!');

     }



     /**
      * Show The Form to Send Custom Emails For Admins 
      * @return View
      *
      */
     public function sendEmailForm(){

        return view('admin.emails.send');

     }



     /**
      * Send Custom Emails For Admins 
      * @param Request $request
      * @return RedirectResponse
      *
      */
     public function sendEmail(Request $request){
        
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
            'reciever' => 'email',
            'subject' => 'required|string'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $body = $request->body;
        $reciever = $request->reciever;
        $subject = $request->subject;

        Mail::to($reciever)->bcc('firas.abb.101@gmail.com')->send(new EmailTo($body, $subject));

        return back()->with('status', 'The email has been sent!');

     }

}
