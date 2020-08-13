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



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * 
     * 
     * Display the Post
     * @param String url
     * @return Response
     * 
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
     * Display posts that are not approved yet.
     *
     * @return \Illuminate\Http\Response
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($categoryUrl = null)
    {

        if($categoryUrl){

            $category = Category::where('url', $categoryUrl)->firstOrFail();
            $licenses = License::all();
            return view('posts.create', ['category' => $category, 'licenses' => $licenses]);

        }
        $categories = Category::with(['medias'])->get();
        return view('categories.select', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $categoryUrl){

        $category = Category::where('url', $categoryUrl)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:15|max:200',
            'description' => 'string|max:500|nullable',
            'tags' => 'string|max:150',
            'license' => 'string|exists:licenses,name',
            'uploads' => 'required|array',
            'uploads.*' => 'file|max:100000|clamav',
            'cover' => 'max:1000|image|nullable|clamav',
            'featured' => 'file|max:20000|mimes:jpeg,bmp,png,mpeg4-generic,ogg,x-wav,x-msvideo,x-ms-wmv,wav,mpga,mp4,x-ms-wmv,x-msvideo|clamav|nullable'
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        if(!Arr::has(Storage::cloud()->directories(), 'downloads')){
            Storage::cloud()->makeDirectory('downloads');
        }
        if(!Arr::has(Storage::cloud()->directories(), 'media')){
            Storage::cloud()->makeDirectory('media');
        }
        if(!Arr::has(Storage::cloud()->directories(), 'featured')){
            Storage::cloud()->makeDirectory('featured');
        }
        if(!Arr::has(Storage::cloud()->directories(), 'covers')){
            Storage::cloud()->makeDirectory('covers');
        }

        $unique = uniqid();

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->user_id = $user->id;
        $url = Str::slug($post->title, '-');
        $checkIfUrlExists = Post::withTrashed()->where('url', 'LIKE', $url)->first();
        if($checkIfUrlExists){
            $url = $url . '-' . $unique;
        }
        $post->url = $url;
        if($user->hasAnyRole('admin', 'moderator')){
            $post->status = 2;
        }
        $post->category()->associate($category);
        $post->save();

        // Featured Media
        $featured = $request->featured;
        if($featured){
            $media = new Media();
            $media->sorting = 1;
            $path = Storage::cloud()->putFile('featured', $featured, 'public');
            $media->url = $path;
            $media->public_url = Storage::cloud()->url($path);
            $media->save();
            $post->medias()->attach($media);
        }

        // Cover
        $cover = $request->cover;
        if($cover){
            $media = new Media();
            $path = Storage::cloud()->putFile('covers', $cover, 'public');
            $media->url = $path;
            $media->sorting = 2;
            $media->public_url = Storage::cloud()->url($path);
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
                $path = $upload->store('downloads', 's3');
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

        if($user->hasAnyRole('admin', 'moderator')){
            return redirect()->route('admin.index.posts')->with('status', 'A New Post Has Been Created');
        }

        return redirect()->route('user.posts.show')->with('status', 'Your Post Has Been Created! Once it is approved, it is going to be public...');

    }

    /** 
    *
    * Approve the post for users not admins
    *
    * @param Request
    * @return Response
    *
    */

    public function adminApprove(Request $request, $id){

        return $this->editOrApprove($id, $request, 2);

    }


    public function adminIndex($posts = null)
    {
        if(!$posts){
            $posts = Post::where('status', 2)->orderBy('id', 'desc')->paginate(10);
        } else {
            $posts = $posts->paginate(20);
        }
        return view('admin.posts.posts', ['posts' => $posts]);
    }


    public function adminShow($id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::all();
        $featured = $post->medias()->where('sorting', 'featured')->first();
        $cover = $post->medias()->where('sorting', 'cover')->first();
        $downloads = $post->downloads;
        return view('admin.posts.show', ['post' => $post, 'featured' => $featured, 'cover' => $cover, 'downloads' => $downloads, 'categories' => $categories]);
    }


    /**
     * 
     * Update the post
     * @param request
     * @return response
     * 
     */
    public function adminEdit(Request $request, $id)
    {
        return $this->editOrApprove($id, $request);
    }


    public function adminDestroy($id)
    {
        $post = Post::findOrFail($id);
        $downloads = $post->downloads;
        foreach($downloads as $download){
            Storage::cloud()->delete($download->url);
        }
        $medias = $post->medias;
        if(!empty($medias)){
            foreach($medias as $media){
                Storage::cloud()->delete($media->url);
                $media->delete();
            }
        }
        $post->delete();
        return redirect('/admin/dashboard/posts/')->with('status', 'The post has been deleted!');
    }



    public function adminDisapprove($id)
    {
        $post = Post::findOrFail($id);
        $downloads = $post->downloads;
        foreach($downloads as $download){
            Storage::cloud()->delete($download->url);
        }
        $medias = $post->medias;
        if(!empty($medias)){
            foreach($medias as $media){
                Storage::cloud()->delete($media->url);
                $media->delete();
            }
        }
        $post->status = 0;
        $post->save();

        // Notify the user
        $post->user->notify(new PostRejected($post));

        return redirect('/admin/dashboard/posts/')->with('status', 'The post has been disapproved!');
    }


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
     * Helper Method To Approve Or Edit The Post
     * @param Integer id
     * @param Integer status
     * 
     */
    private function editOrApprove($id, $request, $status = null){

        $post = Post::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:15|max:200',
            'description' => 'string|max:500|nullable',
            'url' => ['string', Rule::unique('posts', 'url')->ignore($post->url, 'url')],
            'category_id' => 'integer',
            'cover' => 'max:1000|image|nullable|clamav',
            'featured' => 'file|max:20000|mimes:jpeg,bmp,png,mpeg4-generic,ogg,x-wav,x-msvideo,x-ms-wmv,wav,mpga,mp4,x-ms-wmv,x-msvideo|nullable|clamav',
            'upload' => 'array',
            'uploads.*' => 'string|max:100000|clamav'
        ]);

        if($validator->fails()){
            return redirect()->route('admin.show.post', ['id' => $id])->withErrors($validator)->withInput();
        } 

        $post->title = $request->title;
        $post->url = $request->url;
        $post->description = $request->description;
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

        // If there is a cover upload delete the old one if exists
        // And make a new media cover file
        $cover = $request->cover;
        if($cover){
            $oldCover = $post->originalCover();
            if(!empty($oldCover)){
                Storage::cloud()->delete($oldCover->url);
                $oldCover->delete();
            }
            $media = new Media();
            $path = Storage::cloud()->putFile('covers', $cover, 'public');
            $media->url = $path;
            $media->sorting = 2;
            $media->public_url = Storage::cloud()->url($path);
            $media->save();
            $post->medias()->attach($media);
        }


        // If there is a featured upload delete the old one if exists
        // And make a new media cover file
        $featured = $request->featured;
        if($featured){
            $oldFeatured = $post->originalFeatured();
            if(!empty($oldFeatured)){
                Storage::cloud()->delete($oldFeatured->url);
                $oldFeatured->delete();
            }
            $media = new Media();
            $media->sorting = 1;
            $path = Storage::cloud()->putFile('featured', $featured, 'public');
            $media->url = $path;
            $media->public_url = Storage::cloud()->url($path);
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
     * 
     * Delete Post By User
     * @param Request $request
     * @param Integer $id
     * @return Response
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
