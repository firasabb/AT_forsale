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
                $post = Post::where([['url', $url], ['status', 'published']])->with('user')->firstOrFail();
            }
        } else {
            $post = Post::where([['url', $url], ['status', 'published']])->with('user')->firstOrFail();
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
            'uploads' => 'nullable|array',
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
            $post->status = 'published';
        } else {
            $post->status = 'pending';
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
