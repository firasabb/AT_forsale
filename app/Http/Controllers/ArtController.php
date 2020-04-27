<?php

namespace App\Http\Controllers;

use App\Art;
use App\Tag;
use App\Category;
use App\Download;
use App\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Storage;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ArtController extends Controller
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
     * Display the art
     * @param String url
     * @return Response
     * 
     */

    public function show($url){

        $art = Art::where('url', $url)->firstOrFail();
        $featured = $art->featured();
        return view('arts.show', ['art' => $art, 'featured' => $featured]);

    }


    /**
     * Display arts that are not approved yet.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexToApprove()
    {
        $art = Art::where('status', 1)->orderBy('id', 'asc')->first();
        if(!empty($art)){
            $categories = Category::all()->load('medias')->flatten();
            $featured = $art->medias()->where('sorting', 'featured')->first();
            $cover = $art->medias()->where('sorting', 'cover')->first();
            $arts = Art::where('status', 1)->orderBy('id', 'asc');
            $downloads = $art->downloads;
            return view('admin.arts.indexToApprove', ['art' => $art, 'categories' => $categories, 'featured' => $featured, 'cover' => $cover, 'downloads' => $downloads]);
        }
        return view('admin.arts.indexToApprove');

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
            return view('arts.create', ['category' => $category]);

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

        $art = new Art();
        $art->title = $request->title;
        $art->description = $request->description;
        $art->user_id = $user->id;
        $url = Str::slug($art->title, '-');
        $checkIfUrlExists = Art::where('url', 'LIKE', $url)->first();
        if($checkIfUrlExists){
            $url = $url . '-' . $unique;
        }
        $art->url = $url;
        if($user->hasAnyRole('admin', 'moderator')){
            $art->status = 2;
        }
        $art->category()->associate($category);
        $art->save();

        $featured = $request->featured;

        if($featured){
            $media = new Media();
            $media->sorting = 'featured';
            $path = $featured->storePublicly('featured', 's3');
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
            $art->medias()->attach($media);
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
                $art->downloads()->save($download);
            }
        }

        $tags = $request->tags;
        $tags = explode(', ', $tags);
        foreach($tags as $tag){
            $tag = Tag::Where('name', 'LIKE', $tag)->firstOrFail();
            $art->tags()->attach($tag);
        }

        if($user->hasAnyRole('admin', 'moderator')){
            return redirect()->route('admin.index.arts')->with('status', 'A New Art Has Been Created');
        }

        return redirect('/home')->with('status', 'Your Art Has Been Created! Once it is approved, it is going to be public...');

    }

    /** 
    *
    * Approve the art for users not admins
    *
    * @param Request
    * @return Response
    *
    */

    public function adminApprove(Request $request, $id){

        return $this->editOrApprove($id, $request, 2);

    }


    public function adminIndex($arts = null)
    {
        if(!$arts){
            $arts = Art::orderBy('id', 'desc')->paginate(10);
        } else {
            $arts = $arts->paginate(20);
        }
        return view('admin.arts.arts', ['arts' => $arts]);
    }


    public function adminShow($id)
    {
        $art = Art::findOrFail($id);
        $categories = Category::all();
        $featured = $art->medias()->where('sorting', 'featured')->first();
        $cover = $art->medias()->where('sorting', 'cover')->first();
        $downloads = $art->downloads;
        return view('admin.arts.show', ['art' => $art, 'featured' => $featured, 'cover' => $cover, 'downloads' => $downloads, 'categories' => $categories]);
    }


    /**
     * 
     * Update the art
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
        $art = Art::findOrFail($id);
        $downloads = $art->downloads;
        foreach($downloads as $download){
            Storage::cloud()->delete($download->url);
        }
        $medias = $art->medias;
        if(!empty($medias)){
            foreach($medias as $media){
                Storage::cloud()->delete($media->url);
                $media->delete();
            }
        }
        $art->delete();
        return redirect('/admin/dashboard/arts/')->with('status', 'The art has been deleted!');
    }


    public function adminSearchArts(Request $request){
        
        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'title' => 'string|nullable',
            'url' => 'string|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect()->route('admin.index.arts')->withErrors($validator)->withInput();
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

        $arts = Art::where($where_arr);

        if(empty($arts)){
            return $this->adminIndex();
        }
        return $this->adminIndex($arts);
    }



    /**
     * 
     * Helper Method To Approve Or Edit The Art
     * @param Integer id
     * @param Integer status
     * 
     */
    private function editOrApprove($id, $request, $status = null){

        $art = Art::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:15|max:200',
            'description' => 'string|max:500',
            'url' => ['string', Rule::unique('arts', 'url')->ignore($art->url, 'url')],
            'category_id' => 'integer',
            'upload' => 'array',
            'uploads.*' => 'string|max:200'
        ]);

        if($validator->fails()){
            return redirect()->route('admin.show.art', ['id' => $id])->withErrors($validator)->withInput();
        } 

        $art->title = $request->title;
        $art->url = $request->url;
        $art->description = $request->description;
        $category = Category::findOrFail($request->category_id);
        $art->category()->associate($category);
        $tagsArr = array();
        $tags = $request->tags;
        $tags = explode(', ', $tags);
        foreach($tags as $tag){
            $tag = Tag::where('name', 'LIKE', $tag)->first();
            array_push($tagsArr, $tag->id);
        }
        $art->tags()->sync($tagsArr);

        if($status){
            $art->status = $status;
            $art->save();
            return redirect('/admin/dashboard/approve/arts')->with('status', 'The Art has been approved!');
        }
        $art->save();
        return redirect()->route('admin.show.art', ['id' => $id])->with('status', 'This art has been edited');
    }

}
