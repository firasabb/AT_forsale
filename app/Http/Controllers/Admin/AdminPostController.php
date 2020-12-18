<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

class AdminPostController extends Controller
{

    private $downloadFolder = '';


    public function __construct(){

        $downloadFolder = config('filesystems.folders.downloads', 'downloads');
        $featuredFolder = config('filesystems.folders.featured', 'featured');

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
        $post = Post::where('status', 'pending')->orderBy('id', 'asc')->first();
        if(!empty($post)){
            $categories = Category::all()->load('medias')->flatten();
            $featured = $post->medias()->where('sorting', 1)->first();
            $cover = $post->medias()->where('sorting', 2)->first();
            $posts = Post::where('status', 'pending')->orderBy('id', 'asc');
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
    public function adminIndex($order = '', $desc = false, $posts = null)
    {

        // Order By Options For Filtering
        // To show all table columns use the line below and comment the second line
        //$orderByOptions = DB::getSchemaBuilder()->getColumnListing($postsTable);
        $orderByOptions = ['id', 'title'];

        $defaultOrder = 'id';

        if(!$posts){
            if($order){
                if(in_array($order, $orderByOptions) === TRUE){
                    $defaultOrder = $order;
                }
            }
            if($desc){
                $posts = Post::orderBy($defaultOrder, 'desc');
            }
            if(!$desc){
                $posts = Post::orderBy($defaultOrder, 'asc');
            }
        }

        $posts = $posts->paginate(2);

        return view('admin.posts.posts', ['posts' => $posts, 'order' => $order, 'desc' => $desc]);
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
        $post->status = 'disapproved';
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
    public function adminSearch(Request $request){
        
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
        return $this->adminIndex('', false, $posts);
    }


}
