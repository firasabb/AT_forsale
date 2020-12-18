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
