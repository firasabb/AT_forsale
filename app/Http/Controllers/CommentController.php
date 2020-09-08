<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Auth;

class CommentController extends Controller
{
    /**
     * Index Comments For Admins
     * @return RedirectResponse
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
     * Show Comment Info For Admins
     * @param int comment id
     * @return View
     */
    public function adminShow($id)
    {
        $comment = Comment::findOrFail($id);
        return view('admin.comments.show', ['comment' => $comment]);
    }


    /**
     * Delete a Comment For Admins
     * @param int comment id
     * @return RedirectResponse
     */
    public function adminDestroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return redirect()->route('admin.index.comments')->with('status', 'The post has been deleted!');
    }


    /**
     * 
     * Edit a Comment's Info For Admins
     * @param Request $request
     * @return RedirectResponse
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
     * Search Comments For Admins
     * @param Request $request
     * @return adminIndex()
     */
    public function adminSearchComments(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'post_id' => 'integer|nullable',
            'body' => 'string|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect()->route('admin.index.comments')->withErrors($validator)->withInput();
        }

        $id = $request->id;
        $post_id = $request->post_id;
        $body = $request->body;
        $where_arr = array();

        if($id){

            $id_where = ['id', '=', $id];
            array_push($where_arr, $id_where);

        } if($post_id){

            $post_id_where = ['post_id', '=', $post_id];
            array_push($where_arr, $post_id_where);

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


    /**
     * Save a Comment
     * @param string encryptedid
     * @return RedirectResponse
     */
    public function store($encryptedId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|max:100',
        ]);

        if($validator->fails() || !$encryptedId){
            return back()->withErrors()->withInput();
        }
        $post = Post::findOrFail(decrypt($encryptedId));
        $user = Auth::user();
        $comment = new Comment();
        $comment->body = $request->body;
        $comment->post_id = $post->id;
        $user->comments()->save($comment);
        $comment->save();

        return back()->with('status', 'Your comment has been added successfully!');
    }


    /**
     * Delete a Comment
     * @param  string encrypted id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $id = decrypt($id);
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return back()->with('status', 'The comment has been deleted!');
    }
    
}
