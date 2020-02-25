<?php

namespace App\Http\Controllers;

use App\Art;
use App\Category;
use App\Tag;
use App\Type;
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
     * Display arts that are not approved yet.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexToApprove()
    {
        $arts = Art::where('status', 1)->orderBy('id', 'asc')->paginate(1);
        $categories = Category::all();
        $types = Type::with('medias')->flatten()->all();
        //$types = $types->load('medias')->flatten();
        return view('admin.arts.indexToApprove', ['arts' => $arts, 'categories' => $categories, 'types' => $types]);
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type = null)
    {
        if($type){

        }

        $categories = Category::all();
        $types = Type::all();
        return view('arts.create', ['categories' => $categories, 'types' => $types]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:15|max:200',
            'description' => 'string|max:500|nullable',
            'categories' => 'required|array',
            'categories.*' => 'integer',
            'tags' => 'string|max:150',
            'type' => 'required|string|exists:types,name',
            'uploads' => 'required|array',
            'uploads.*' => 'file|max:100000',
            'featured' => 'file|max:20000|mimes:jpeg,bmp,png,mpeg4-generic,ogg,x-wav,x-msvideo,x-ms-wmv',
            'cover' => 'file|mimes:jpeg,bmp,png|nullable'
        ]);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        if(!Arr::has(Storage::cloud()->directories(), 'downloads')){
            Storage::cloud()->makeDirectory('downloads');
        }
        if(!Arr::has(Storage::cloud()->directories(), 'featured')){
            Storage::cloud()->makeDirectory('featured');
        }
        if(!Arr::has(Storage::cloud()->directories(), 'media')){
            Storage::cloud()->makeDirectory('media');
        }


        $type = Type::where('name', $request->type)->firstOrFail();

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
        $art->type()->associate($type);
        $art->save();

        $featured = $request->featured;

        $download = new Download();
        $download->name = $art->title;
        $download->featured = 1;
        $path = $featured->store('featured/' . $unique, 's3');
        $download->url = $path;
        $download->art()->associate($art);
        $download->save();

        $uploads = $request->uploads;
        if($uploads){
            foreach($uploads as $upload){
                $download = new Download();
                $download->name = $art->title;
                $path = $upload->store('downloads/' . uniqid(), 's3');
                $download->url = $path;
                $art->downloads()->save($download);
            }
        }

        $categories = $request->categories;
        foreach($categories as $category){
            $category = Category::findOrFail($category);
            $art->categories()->attach($category);
        }

        $tags = $request->tags;
        $tags = explode(', ', $tags);
        foreach($tags as $tag){
            $tag = Tag::Where('name', 'LIKE', $tag)->firstOrFail();
            $art->tags()->attach($tag);
        }

        if($request->cover){
            $media = new Media();
            $uploadedCover = $request->cover;
            $path = $uploadedCover->storePublicly('media/' . $unique, 's3');
            $media->url = $path;
            $media->sorting = 'cover';
            $art->medias()->attach($art);
            $media->save();
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

        $this->editOrApprove($id, $request, 2);
        return redirect('/admin/dashboard/approve/arts')->with('status', 'The Art has been approved!');

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
        $types = Type::all();
        $categories = Category::all();
        $hasCategories = $art->categories->pluck('id');
        return view('admin.arts.show', ['art' => $art, 'categories' => $categories, 'hasCategories' => $hasCategories, 'types' => $types]);
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
        $this->editOrApprove($id, $request);
        return redirect()->route('admin.show.art', ['id' => $id])->with('status', 'This category has been edited');
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
            'description' => 'string|max:500|nullable',
            'categories' => 'required|array',
            'categories.*' => 'integer',
            'url' => ['string', Rule::unique('arts', 'url')->ignore($art->url, 'url')],
            'tags' => 'string|max:150',
            'type_id' => 'integer',
            'upload' => 'array',
            'uploads.*' => 'string|max:200'
        ]);

        if($validator->fails()){
            return redirect()->route('admin.show.art', ['id' => $id])->withErrors($validator)->withInput();
        } 

        $art->title = $request->title;
        $art->url = $request->url;
        $art->description = $request->description;
        $type = Type::findOrFail($request->type_id);
        $art->type()->associate($type);
        $art->categories()->sync($request->categories);
        $tagsArr = array();
        $tags = $request->tags;
        $tags = explode(', ', $tags);
        foreach($tags as $tag){
            $tag = Tag::where('name', 'LIKE', $tag)->first();
            array_push($tagsArr, $tag->id);
        }
        $art->tags()->sync($tagsArr);
        //$art->options = $request->options;

        if($status){
            $art->status = $status;
        }
        $art->save();

    }

}
