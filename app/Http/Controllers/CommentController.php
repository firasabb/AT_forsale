<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Asset;
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
            'body' => 'required|max:100',
        ]);

        if($validator->fails() || !$encryptedId){
            return redirect()->back()->withErrors()->withInput();
        }
        $asset = Asset::findOrFail(decrypt($encryptedId));
        $user = Auth::user();
        $comment = new Comment();
        $comment->body = $request->body;
        $comment->asset_id = $asset->id;
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
        return redirect()->route('admin.index.comments')->with('status', 'The asset has been deleted!');
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
            'body' => 'required|string|max:40'
        ]);

        if($validator->fails()){
            return redirect()->route('admin.show.comment', ['id' => $id])->withErrors($validator)->withInput();
        } 

        $comment->body = $request->body;
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
    public function destroy($id)
    {
        $id = decrypt($id);
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return back()->with('stats', 'The comment has been deleted!');
    }


    public function adminSearchComments(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'asset_id' => 'integer|nullable',
            'body' => 'string|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect()->route('admin.index.comments')->withErrors($validator)->withInput();
        }

        $id = $request->id;
        $asset_id = $request->asset_id;
        $body = $request->body;
        $where_arr = array();

        if($id){

            $id_where = ['id', '=', $id];
            array_push($where_arr, $id_where);

        } if($asset_id){

            $asset_id_where = ['asset_id', '=', $asset_id];
            array_push($where_arr, $asset_id_where);

        } if($body){

            $body_where = ['body', 'LIKE', '%' . $body . '%'];
            array_push($where_arr, $body_where);

        }

        $comments = Comment::where($where_arr);

        if(empty($comments)){
            return $this->adminIndex();
        }
        return $this->adminIndex($comments);
    }
}
