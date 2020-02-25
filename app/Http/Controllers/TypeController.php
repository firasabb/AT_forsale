<?php

namespace App\Http\Controllers;

use App\Type;
use App\Media;
use Illuminate\Http\Request;
use Validator;
use URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;



class TypeController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        //
    }


    public function adminIndex($types = null)
    {
        if(!$types){
            $types = Type::orderBy('id', 'desc')->paginate(20);
        } else {
            $types = $types->paginate(20);
        }
        return view('admin.types.types', ['types' => $types]);
    }


    public function adminAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'url' => 'required|string',
            'featured' => 'file|mimes:png,jpg|max:2000|nullable'
        ]);

        if($validator->fails()){
            return redirect('/admin/dashboard/types')->withErrors($validator)->withInput();
        } 

        $type = new Type();
        $type->name = strToLower($request->name);
        $request->url = Str::slug($request->url, '-');
        $check = Type::where(['deleted_at' => NULL, 'url' => $request->url])->first();
        if(!empty($check)){
            return redirect('/admin/dashboard/types/')->withErrors('The url has already been taken.')->withInput();
        }
        $type->url = $request->url;
        $type->save();

        if($request->featured){
            $media = new Media();
            $uploadedFile = $request->featured;
            $unique = uniqid();
            $path = $uploadedFile->storePublicly('media/' . $unique ,'s3');
            $media->url = $path;
            $media->sorting = 'featured';
            $media->save();
            $type->medias()->attach($media);
        }

        return redirect('/admin/dashboard/types')->with('status', 'A new type has been created!');


    }


    public function adminEdit(Request $request, $id)
    {
        $type = Type::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:40',
            'url' => 'string',
            'featured' => 'file|mimes:png,jpg|max:2000|nullable'
        ]);

        if($validator->fails()){
            return redirect('/admin/dashboard/type/' . $id)->withErrors($validator)->withInput();
        } 

        $type->name = strToLower($request->name);
        $request->url = Str::slug($request->url, '-');
        if($request->url != $type->url){
            $check = Type::where(['deleted_at' => NULL, 'url' => $request->url])->first();
            if(!empty($check)){
                return redirect('/admin/dashboard/type/' . $id)->withErrors('The url has already been taken.')->withInput();
            }
            $type->url = $request->url;
        }
        $type->save();

        if($request->featured){
            $unique = uniqid();
            $uploadedFile = $request->featured;
            if(!empty($type->medias->first())){
                $media = $type->medias()->first();
                $path = $uploadedFile->storePublicly('media/' . $unique, 's3');
                $media->url = $path;
                $media->save();
            } else {
                $media = new Media();
                $path = $uploadedFile->storePublicly('media/' . $unique, 's3');
                $media->url = $path;
                $media->sorting = 'featured';
                $media->save();
                $type->medias()->attach($media);
            }
        }


        return redirect('/admin/dashboard/type/' . $id)->with('status', 'This type has been edited');
    }


    public function adminShow($id)
    {
        $type = Type::findOrFail($id);
        return view('admin.types.show', ['type' => $type]);
    }


    public function adminDestroy($id)
    {
        $type = Type::findOrFail($id);
        $type->delete();
        return redirect('/admin/dashboard/types/')->with('status', 'A type has been deleted!');
    }


    public function adminSearchTypes(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'name' => 'string|max:300|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect('/admin/dashboard/types/')->withErrors($validator)->withInput();
        }

        $id = $request->id;
        $name = $request->name;
        
        $where_arr = array();

        if ($id){

            $id_where = ['id', '=', $id];
            array_push($where_arr, $id_where);

        } if($name){

            $name_where = ['name', 'LIKE', '%' . $name . '%'];
            array_push($where_arr, $name_where);

        }

        $types = Type::where($where_arr);

        if(empty($types)){
            return $this->adminIndex();
        }
        return $this->adminIndex($types);
    }

}
