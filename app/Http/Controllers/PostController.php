<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tag;
use App\Category;
use App\Download;
use App\Media;
use App\DownloadEvent;
use App\ViewEvent;
use App\License;
use App\Notifications\PostApproved;
use App\Notifications\PostRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Storage;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class PostController extends Controller
{

    private $downloadFolder = '';


    public function __construct(){

        $downloadFolder = config('filesystems.folders.downloads', 'downloads');
        $featuredFolder = config('filesystems.folders.featured', 'featured');

    }


    /**
     * Display the Post
     * @param String url
     * @return View
     */

    public function show($url){

        // get the user to check if admin or moderator
        $user = Auth::user();

        // if admin then show the post even if it is not approved
        if($user){
            if($user->hasAnyRole(['admin', 'moderator'])){
                $post = Post::where('url', $url)->with('user')->firstOrFail();
            } else {
                $post = Post::where([['url', $url], ['status', 2]])->with('user')->firstOrFail();
            }
        } else {
            $post = Post::where([['url', $url], ['status', 2]])->with('user')->firstOrFail();
        }
        $featured = $post->featured();
        $license = $post->licenses()->first();
        $category = $post->category;
        $relatedPosts = $category->approvedPosts()->inRandomOrder()->where('id', '!=', $post->id)->take(6)->get();
        $dataArr = ['post' => $post, 'featured' => $featured, 'license' => $license, 'relatedPosts' => $relatedPosts];
        $ip = $_SERVER['REMOTE_ADDR'];
        $checkPastViews = $post->viewEvents()->where(function($query) use ($ip) {
            if(Auth::check()){
                $query->where('ip_address', $ip)->orWhere('user_id', Auth::id());
            } else {
                $query->where('ip_address', $ip);
            }
        });
        if(empty($checkPastViews->first())){
            $viewEvent = new ViewEvent();
            $viewEvent->ip_address = $ip;
            $viewEvent->post()->associate($post);
            if(Auth::check()){
                $viewEvent->user()->associate(Auth::user());
            }
            $viewEvent->save();
        }
        $checkPastDownloads = DownloadEvent::whereDate('created_at', Carbon::today())->count();
        //if($checkPastDownloads > 2 && !Auth::check()){
            //$error = 'Maximum limit of downloads per day has been reached. Please log in or register to continue.';
            //return view('posts.show', $dataArr)->withErrors($error);
        //}
        return view('posts.show', $dataArr);

    }

    /**
     * Show the form for creating a new post.
     * @return View
     */
    public function create()
    {

        $licenses = License::all();
        $categories = Category::all();
        return view('posts.create', ['licenses' => $licenses, 'categories'  => $categories]);
        
    }


    /**
     * Store a newly created post in storage.
     * 
     * PLEASE NOTE: ClamAV Laravel package is installed, to scan the uploaded files, you can add clamav as a rule
     * For example: 'featured' => 'file|clamav'
     * 
     * We advise you to use it or to use a similar AV to scan the uploaded files
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string $categoryUrl
     * @return RedirectResponse
     */
    public function store(Request $request){


        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:15|max:200',
            'description' => 'string|max:500|nullable',
            'category' => 'required|string|exists:categories,url',
            'tags' => 'string|max:150',
            'license' => 'string|exists:licenses,name',
            'uploads' => 'required|array',
            'uploads.*' => 'file|max:100000',
            'cover' => 'max:1000|image|nullable',
            'featured' => 'file|max:20000|mimes:jpeg,bmp,png,mpeg4-generic,ogg,x-wav,x-msvideo,x-ms-wmv,wav,mpga,mp4,x-ms-wmv,x-msvideo|nullable'
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        if(!Arr::has(Storage::directories(), 'downloads')){
            Storage::makeDirectory('downloads');
        }
        if(!Arr::has(Storage::directories(), 'media')){
            Storage::makeDirectory('media');
        }
        if(!Arr::has(Storage::directories(), 'featured')){
            Storage::makeDirectory('featured');
        }
        if(!Arr::has(Storage::directories(), 'covers')){
            Storage::makeDirectory('covers');
        }


        $category = Category::where('url', 'LIKE', $request->category)->firstOrFail();

        // Create a unique id
        $unique = uniqid();

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->user_id = $user->id;
        $url = Str::slug($post->title, '-');
        // In case the url exists in a previous post add the unique id to the column
        $checkIfUrlExists = Post::withTrashed()->where('url', 'LIKE', $url)->first();
        if($checkIfUrlExists){
            $url = $url . '-' . $unique;
        }
        $post->url = $url;
        
        // If the post was added by an admin set the status to approved
        if($user->hasAnyRole('admin')){
            $post->status = 2;
        } else {
            $post->status = 1;
        }
        
        $post->category()->associate($category);
        $post->save();

        // Featured Media
        $featured = $request->featured;
        if($featured){
            $media = new Media();
            $media->sorting = 1;
            $path = Storage::putFile($featuredFolder, $featured, 'public');
            $media->url = $path;
            $media->public_url = Storage::url($path);
            $media->save();
            $post->medias()->attach($media);
        }

        // Uploads
        $uploads = $request->uploads;
        if($uploads){
            foreach($uploads as $upload){
                $download = new Download();
                $name = Str::slug($upload->getClientOriginalName(), '_');
                $extension = $upload->getClientOriginalExtension();
                $name = str_replace($extension, '', $name);
                $download->name = $name;
                $download->extension = $extension;
                $path = Storage::putFile($downloadFolder, $upload);
                $download->url = $path;
                $post->downloads()->save($download);
            }
        }

        // Tags
        $tags = $request->tags;
        $tags = explode(', ', $tags);
        foreach($tags as $tag){
            $tag = Tag::Where('name', 'LIKE', $tag)->firstOrFail();
            $post->tags()->attach($tag);
        }

        // Licenses
        $license = $request->license;
        $license = License::where('name', 'LIKE', $license)->firstOrFail();
        $post->licenses()->attach($license);

        if($user->hasAnyRole('admin')){
            return redirect()->route('admin.index.posts')->with('status', 'A New Post Has Been Created');
        }

        return redirect()->route('user.posts.show')->with('status', 'Your Post Has Been Created! Once it is approved, it is going to be public...');

    }




    /**
     * 
     * Helper Method To Approve Or Edit The Post
     * 
     * 
     * PLEASE NOTE: ClamAV Laravel package is installed, to scan the uploaded files, you can add clamav as a rule
     * For example: 'featured' => 'file|clamav'
     * 
     * We advise you to use it or to use a similar AV to scan the uploaded files
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  string $categoryUrl
     * @return RedirectResponse
     * 
     * 
     * @param Integer id
     * @param Request
     * @param Integer status
     * @return RedirectResponse
     * 
     */
    private function editOrApprove($id, $request, $status = null){

        $post = Post::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:15|max:200',
            'description' => 'string|max:500|nullable',
            'url' => ['string', Rule::unique('posts', 'url')->ignore($post->url, 'url')],
            'category_id' => 'integer',
            'cover' => 'max:1000|image|nullable',
            'featured' => 'file|max:20000|mimes:jpeg,bmp,png,mpeg4-generic,ogg,x-wav,x-msvideo,x-ms-wmv,wav,mpga,mp4,x-ms-wmv,x-msvideo|nullable',
            'upload' => 'array',
            'uploads.*' => 'string|max:100000'
        ]);

        if($validator->fails()){
            return redirect()->route('admin.show.post', ['id' => $id])->withErrors($validator)->withInput();
        } 

        $post->title = $request->title;
        $post->url = $request->url;
        $post->description = $request->description;
        $license = License::findOrFail($request->license_id);
        $category = Category::findOrFail($request->category_id);
        $post->category()->associate($category);
        $tagsArr = array();
        $tags = $request->tags;
        $tags = explode(', ', $tags);
        foreach($tags as $tag){
            $tag = Tag::where('name', 'LIKE', $tag)->first();
            array_push($tagsArr, $tag->id);
        }
        $post->tags()->sync($tagsArr);

        // If there is a featured upload delete the old one if exists
        // And make a new media cover file
        $featured = $request->featured;
        if($featured){
            $oldFeatured = $post->originalFeatured();
            if(!empty($oldFeatured)){
                Storage::delete($oldFeatured->url);
                $oldFeatured->delete();
            }
            $media = new Media();
            $media->sorting = 1;
            $path = Storage::putFile($featuredFolder, $featured, 'public');
            $media->url = $path;
            $media->public_url = Storage::url($path);
            $media->save();
            $post->medias()->attach($media);
        }

        // If the argument status is set then approve if not edit
        if($status){
            $post->status = $status;
            $post->save();
            
            // Notify
            $post->user->notify(new PostApproved($post));

            return redirect('/admin/dashboard/approve/posts')->with('status', 'The post has been approved!');
        }
        $post->save();
        return redirect()->route('admin.show.post', ['id' => $id])->with('status', 'This post has been edited');
    }


    
    /**
     * Display posts that are not approved yet for ADMINS.
     * @return View
     */
    public function indexToApprove()
    {
        $post = Post::where('status', 1)->orderBy('id', 'asc')->first();
        if(!empty($post)){
            $categories = Category::all()->load('medias')->flatten();
            $featured = $post->medias()->where('sorting', 1)->first();
            $cover = $post->medias()->where('sorting', 2)->first();
            $posts = Post::where('status', 1)->orderBy('id', 'asc');
            $downloads = $post->downloads;
            return view('admin.posts.indexToApprove', ['post' => $post, 'categories' => $categories, 'featured' => $featured, 'cover' => $cover, 'downloads' => $downloads]);
        }
        return view('admin.posts.indexToApprove');

    }


    /** 
    * Approve the post for users
    * @param Request
    * @param int $id
    * @return editOrApprove($id, $request, 2)
    */

    public function adminApprove(Request $request, $id){

        return $this->editOrApprove($id, $request, 2);

    }

    /** 
    * Index the posts for admins
    * @param array $posts
    * @return View
    */
    public function adminIndex($posts = null)
    {
        if(!$posts){
            $posts = Post::where('status', 2)->orderBy('id', 'desc')->paginate(10);
        } else {
            $posts = $posts->paginate(20);
        }
        return view('admin.posts.posts', ['posts' => $posts]);
    }


    /** 
    * Show the post for admins
    * @param int $id
    * @return View
    */
    public function adminShow($id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::all();
        $licenses = License::all();
        $featured = $post->medias()->where('sorting', 'featured')->first();
        $cover = $post->medias()->where('sorting', 'cover')->first();
        $downloads = $post->downloads;
        return view('admin.posts.show', ['post' => $post, 'featured' => $featured, 'cover' => $cover, 'downloads' => $downloads, 'categories' => $categories, 'licenses' => $licenses]);
    }


    /**
     * 
     * Update the post for admins
     * @param Request
     * @param int $id
     * @return editOrApprove($id, $request)
     * 
     */
    public function adminEdit(Request $request, $id)
    {
        return $this->editOrApprove($id, $request);
    }


    /** 
    * Delete the post for admins.
    * @param int $id
    * @return RedirectResponse
    */
    public function adminDestroy($id)
    {
        $post = Post::findOrFail($id);
        $downloads = $post->downloads;
        foreach($downloads as $download){
            Storage::delete($download->url);
        }
        $medias = $post->medias;
        if(!empty($medias)){
            foreach($medias as $media){
                Storage::delete($media->url);
                $media->delete();
            }
        }
        $post->delete();
        return redirect('/admin/dashboard/posts/')->with('status', 'The post has been deleted!');
    }


    /** 
    * Disapprove a post for admins.
    * @param int $id
    * @return RedirectResponse
    */
    public function adminDisapprove($id)
    {
        $post = Post::findOrFail($id);
        $downloads = $post->downloads;
        foreach($downloads as $download){
            Storage::delete($download->url);
        }
        $medias = $post->medias;
        if(!empty($medias)){
            foreach($medias as $media){
                Storage::delete($media->url);
                $media->delete();
            }
        }
        $post->status = 0;
        $post->save();

        // Notify the user
        $post->user->notify(new PostRejected($post));

        return redirect('/admin/dashboard/posts/')->with('status', 'The post has been disapproved!');
    }


    /** 
    * Search posts for admins.
    * @param Request
    * @return RedirectResponse
    */
    public function adminSearchPosts(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'title' => 'string|nullable',
            'url' => 'string|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect()->route('admin.index.posts')->withErrors($validator)->withInput();
        }

        $id = $request->id;
        $title = $request->title;
        $url = Str::slug($request->url);
        
        $where_arr = array();

        if($id){

            $id_where = ['id', '=', $id];
            array_push($where_arr, $id_where);

        } if($title){

            $title_where = ['title', 'LIKE', '%' . $title . '%'];
            array_push($where_arr, $title_where);

        } if($url){

            $url_where = ['url', 'LIKE', '%' . $url . '%'];
            array_push($where_arr, $url_where);

        }

        $posts = Post::where($where_arr);

        if(empty($posts)){
            return $this->adminIndex();
        }
        return $this->adminIndex($posts);
    }



    /**
     * 
     * Delete Post By User
     * @param Request $request
     * @param Integer $id
     * @return RedirectResponse
     * 
     */
    public function destroy(Request $request, $id){

        $id = decrypt($id);
        $user = Auth::user();
        $post = $user->posts()->findOrFail($id);
        $post->delete();
        return back()->with('status', 'Your Post Has Been Deleted!');
    }

}
