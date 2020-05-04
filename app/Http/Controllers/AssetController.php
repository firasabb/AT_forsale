<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Tag;
use App\Category;
use App\Download;
use App\Media;
use App\DownloadEvent;
use App\ViewEvent;
use App\License;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Storage;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class AssetController extends Controller
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
     * Display the Asset
     * @param String url
     * @return Response
     * 
     */

    public function show($url){

        $asset = Asset::where('url', $url)->firstOrFail();
        $featured = $asset->featured();
        $license = $asset->licenses()->first();
        $category = $asset->category;
        $relatedAssets = $category->approvedAssets()->inRandomOrder()->where('id', '!=', $asset->id)->take(6)->get();
        //dd($relatedAssets);
        $dataArr = ['asset' => $asset, 'featured' => $featured, 'license' => $license, 'relatedAssets' => $relatedAssets];
        $ip = $_SERVER['REMOTE_ADDR'];

        $checkPastViews = $asset->viewEvents()->where(function($query) use ($ip) {
            if(Auth::check()){
                $query->where('ip_address', $ip)->orWhere('user_id', Auth::id());
            } else {
                $query->where('ip_address', $ip);
            }
        });
        if(empty($checkPastViews->first())){
            $viewEvent = new ViewEvent();
            $viewEvent->ip_address = $ip;
            $viewEvent->asset()->associate($asset);
            if(Auth::check()){
                $viewEvent->user()->associate(Auth::user());
            }
            $viewEvent->save();
        }
        $checkPastDownloads = DownloadEvent::whereDate('created_at', Carbon::today())->count();
        if($checkPastDownloads > 2 && !Auth::check()){
            $error = 'Maximum limit of downloads per day has been reached. Please log in or register to continue.';
            return view('assets.show', $dataArr)->withErrors($error);
        }
        return view('assets.show', $dataArr);

    }


    /**
     * Display assets that are not approved yet.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexToApprove()
    {
        $asset = Asset::where('status', 1)->orderBy('id', 'asc')->first();
        if(!empty($asset)){
            $categories = Category::all()->load('medias')->flatten();
            $featured = $asset->medias()->where('sorting', 'featured')->first();
            $cover = $asset->medias()->where('sorting', 'cover')->first();
            $assets = Asset::where('status', 1)->orderBy('id', 'asc');
            $downloads = $asset->downloads;
            return view('admin.assets.indexToApprove', ['asset' => $asset, 'categories' => $categories, 'featured' => $featured, 'cover' => $cover, 'downloads' => $downloads]);
        }
        return view('admin.assets.indexToApprove');

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
            return view('assets.create', ['category' => $category]);

        }
        $categories = Category::with('medias')->get();
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
            'description' => 'string|max:500',
            'tags' => 'string|max:150',
            'uploads' => 'required|array',
            'uploads.*' => 'file|max:100000',
            'cover' => 'file|max:1000|mimes:jpeg,bmp,png',
            'featured' => 'file|max:20000|mimes:jpeg,bmp,png,mpeg4-generic,ogg,x-wav,x-msvideo,x-ms-wmv'
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

        $asset = new Asset();
        $asset->title = $request->title;
        $asset->description = $request->description;
        $asset->user_id = $user->id;
        $url = Str::slug($asset->title, '-');
        $checkIfUrlExists = Asset::where('url', 'LIKE', $url)->first();
        if($checkIfUrlExists){
            $url = $url . '-' . $unique;
        }
        $asset->url = $url;
        if($user->hasAnyRole('admin', 'moderator')){
            $asset->status = 2;
        }
        $asset->category()->associate($category);
        $asset->save();

        $featured = $request->featured;

        if($featured){
            $media = new Media();
            $media->sorting = 'featured';
            //$path = Storage::cloud()->putFile('featured', $featured, 'public');
            $path = $featured->store('featured', 's3', 'public');
            $media->url = $path;
            $media->save();
        }

        if($request->cover){
            $media = new Media();
            $uploadedCover = $request->cover;
            $path = $uploadedCover->storePublicly('covers', 's3');
            $media->url = $path;
            $media->sorting = 'cover';
            $media->public_url = Storage::cloud()->url($path);
            $media->save();
            $asset->medias()->attach($media);
        }


        $uploads = $request->uploads;
        if($uploads){
            foreach($uploads as $upload){
                $download = new Download();
                $name = Str::slug($upload->getClientOriginalName(), '_');
                $extension = $upload->getClientOriginalExtension();
                $name = str_replace('.' . $extension, '', $name);
                $download->name = $name;
                $path = $upload->store('downloads', 's3');
                $download->url = $path;
                $asset->downloads()->save($download);
            }
        }

        $tags = $request->tags;
        $tags = explode(', ', $tags);
        foreach($tags as $tag){
            $tag = Tag::Where('name', 'LIKE', $tag)->firstOrFail();
            $asset->tags()->attach($tag);
        }

        $asset->licenses()->attach(License::find(1));

        if($user->hasAnyRole('admin', 'moderator')){
            return redirect()->route('admin.index.assets')->with('status', 'A New Asset Has Been Created');
        }

        return redirect('/home')->with('status', 'Your Asset Has Been Created! Once it is approved, it is going to be public...');

    }

    /** 
    *
    * Approve the asset for users not admins
    *
    * @param Request
    * @return Response
    *
    */

    public function adminApprove(Request $request, $id){

        return $this->editOrApprove($id, $request, 2);

    }


    public function adminIndex($assets = null)
    {
        if(!$assets){
            $assets = Asset::orderBy('id', 'desc')->paginate(10);
        } else {
            $assets = $assets->paginate(20);
        }
        return view('admin.assets.assets', ['assets' => $assets]);
    }


    public function adminShow($id)
    {
        $asset = Asset::findOrFail($id);
        $categories = Category::all();
        $featured = $asset->medias()->where('sorting', 'featured')->first();
        $cover = $asset->medias()->where('sorting', 'cover')->first();
        $downloads = $asset->downloads;
        return view('admin.assets.show', ['asset' => $asset, 'featured' => $featured, 'cover' => $cover, 'downloads' => $downloads, 'categories' => $categories]);
    }


    /**
     * 
     * Update the asset
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
        $asset = Asset::findOrFail($id);
        $downloads = $asset->downloads;
        foreach($downloads as $download){
            Storage::cloud()->delete($download->url);
        }
        $medias = $asset->medias;
        if(!empty($medias)){
            foreach($medias as $media){
                Storage::cloud()->delete($media->url);
                $media->delete();
            }
        }
        $asset->delete();
        return redirect('/admin/dashboard/assets/')->with('status', 'The asset has been deleted!');
    }


    public function adminSearchAssets(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'title' => 'string|nullable',
            'url' => 'string|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect()->route('admin.index.assets')->withErrors($validator)->withInput();
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

        $assets = Asset::where($where_arr);

        if(empty($assets)){
            return $this->adminIndex();
        }
        return $this->adminIndex($assets);
    }



    /**
     * 
     * Helper Method To Approve Or Edit The Asset
     * @param Integer id
     * @param Integer status
     * 
     */
    private function editOrApprove($id, $request, $status = null){

        $asset = Asset::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:15|max:200',
            'description' => 'string|max:500',
            'url' => ['string', Rule::unique('assets', 'url')->ignore($asset->url, 'url')],
            'category_id' => 'integer',
            'upload' => 'array',
            'uploads.*' => 'string|max:200'
        ]);

        if($validator->fails()){
            return redirect()->route('admin.show.asset', ['id' => $id])->withErrors($validator)->withInput();
        } 

        $asset->title = $request->title;
        $asset->url = $request->url;
        $asset->description = $request->description;
        $category = Category::findOrFail($request->category_id);
        $asset->category()->associate($category);
        $tagsArr = array();
        $tags = $request->tags;
        $tags = explode(', ', $tags);
        foreach($tags as $tag){
            $tag = Tag::where('name', 'LIKE', $tag)->first();
            array_push($tagsArr, $tag->id);
        }
        $asset->tags()->sync($tagsArr);

        if($status){
            $asset->status = $status;
            $asset->save();
            return redirect('/admin/dashboard/approve/assets')->with('status', 'The asset has been approved!');
        }
        $asset->save();
        return redirect()->route('admin.show.asset', ['id' => $id])->with('status', 'This asset has been edited');
    }


    /**
     * 
     * Delete Asset By User
     * @param Request $request
     * @param Integer $id
     * @return Response
     * 
     */
    public function destroy(Request $request, $id){

        $id = decrypt($id);
        $user = Auth::user();
        $asset = $user->assets()->findOrFail($id);
        $asset->delete();
        return back()->with('status', 'Your Asset Has Been Deleted!');
    }

}
