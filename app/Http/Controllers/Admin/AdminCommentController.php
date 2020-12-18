<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Comment;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Auth;

class AdminCommentController extends Controller
{
    /**
     * Index Comments For Admins
     * @return RedirectResponse
     */
    public function adminIndex($order = '', $desc = false, $comments = null)
    {

        // Order By Options For Filtering
        // To show all table columns use the line below and comment the second line
        //$orderByOptions = DB::getSchemaBuilder()->getColumnListing($commentsTable);
        $orderByOptions = ['id', 'body', 'post_id'];

        $defaultOrder = 'id';

        if(!$comments){
            if($order){
                if(in_array($order, $orderByOptions) === TRUE){
                    $defaultOrder = $order;
                }
            }
            if($desc){
                $comments = Comment::orderBy($defaultOrder, 'desc');
            }
            if(!$desc){
                $comments = Comment::orderBy($defaultOrder, 'asc');
            }
        }

        $comments = $comments->paginate(20);

        return view('admin.comments.comments', ['comments' => $comments, 'order' => $order, 'desc' => $desc]);
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
    public function adminSearch(Request $request){

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
        return $this->adminIndex('', false, $comments);
    }
    
}
