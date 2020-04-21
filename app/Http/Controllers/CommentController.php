<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Art;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex($comments = null)
    {
        if(!$comments){
            $comments = Comment::orderBy('id', 'desc')->paginate(20);
        } else {
            $comments = $comments->paginate(20);
        }
        return view('admin.comments.comments', ['comments' => $comments]);
    }

    /**
     * Save the comment into the database.
     * @param encryptedid
     * @return \Illuminate\Http\Response
     */
    public function store($encryptedId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'description' => 'max:2500|nullable',
        ]);

        if($validator->fails() || !$encryptedId){
            return redirect()->back()->withErrors()->withInput();
        }
        $art = Art::findOrFail(decrypt($encryptedId));
        $user = Auth::user();
        $comment = new Comment();
        $comment->title = $request->title;
        $comment->description = $request->description;
        $comment->art_id = $art->id;
        $user->comments()->save($comment);
        $comment->save();

        return redirect()->back()->with('status', 'Your comment has been added successfully!');
    }



    /**
     * Show comment page for admins and moderators
     * 
     * @param comment id
     * @return response
     */
    public function adminShow($id)
    {
        $comment = Comment::findOrFail($id);
        return view('admin.comments.show', ['comment' => $comment]);
    }


    /**
     * Delete comment for admins and moderators
     * 
     * @param comment id
     * @return response
     */
    public function adminDestroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return redirect()->route('admin.index.comments')->with('status', 'The art has been deleted!');
    }


    /**
     * 
     * Update the comment
     * @param request
     * @return response
     * 
     */
    public function adminEdit(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:40',
            'description' => 'string'
        ]);

        if($validator->fails()){
            return redirect()->route('admin.show.comment', ['id' => $id])->withErrors($validator)->withInput();
        } 

        $comment->title = $request->title;
        $comment->description = $request->description;
        $comment->save();

        return redirect()->route('admin.show.comment', ['id' => $id])->with('status', 'This comment has been edited');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }


    public function adminSearchComments(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'art_id' => 'integer|nullable',
            'title' => 'string|nullable',
            'description' => 'string|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect()->route('admin.index.comments')->withErrors($validator)->withInput();
        }

        $id = $request->id;
        $art_id = $request->art_id;
        $title = $request->title;
        $description = $request->description;
        $where_arr = array();

        if($id){

            $id_where = ['id', '=', $id];
            array_push($where_arr, $id_where);

        } if($art_id){

            $art_id_where = ['art_id', '=', $art_id];
            array_push($where_arr, $art_id_where);

        } if($title){

            $title_where = ['title', 'LIKE', '%' . $title . '%'];
            array_push($where_arr, $title_where);

        } if($description){

            $description_where = ['description', 'LIKE', '%' . $description . '%'];
            array_push($where_arr, $description_where);

        }

        $comments = Comment::where($where_arr);

        if(empty($comments)){
            return $this->adminIndex();
        }
        return $this->adminIndex($comments);
    }
}
