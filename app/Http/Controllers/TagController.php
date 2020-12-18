<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Category;
use Illuminate\Http\Request;
use Validator;
use URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{

    /**
     * Index tags.
     * @return View
     */
    public function index()
    {
        $tags = Tag::orderBy('id', 'desc')->paginate(20);
        return view('tags.tags', ['tags' => $tags]);
    }


    /**
     * 
     * Search For Tags in an AJAX Request
     * @param Request
     * @return Response
     * 
     */
    public function suggestTags(Request $request){

        if($request->ajax()){

            $validator = Validator::make($request->all(), [
                'tag' => 'string|nullable',
                'exist' => 'array|nullable'
            ]);

            if($validator->fails()){
                $response = array(
                    'status' => 'error',
                    'response' => $validator
                );
                return response()->json($response);
            }

            $tag = $request->tag;
            $exist = $request->exist;

            $whereArr = array();
            if($exist){
                foreach($exist as $existTag){
                    $where = ['name', '!=', $existTag];
                    array_push($whereArr, $where);
                }
            }
            if(!empty($tag)){
                $where = ['name', 'LIKE', '%' . $tag . '%'];
                array_push($whereArr, $where);
            }
            
            $searchResults = Tag::where($whereArr)->get();

            $response = array(
                'status' => 'success',
                'results' => $searchResults
            );
    
            return response()->json($response);
        }
        
    }


}
