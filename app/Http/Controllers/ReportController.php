<?php

namespace App\Http\Controllers;

use App\Report;
use App\Post;
use App\Comment;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Crypt;
use Auth;
use DB;

class ReportController extends Controller
{


    /** 
    * Store a report.
    * @param int $id
    * @return RedirectResponse
    */
    public function store(Request $request, $type){

        $validator = Validator::make($request->all(), [
            '_q' => 'required|string',
            'body' => 'required|string'
        ]);

        if($validator->fails()){

            return back()->withErrors($validator)->withInput();

        }

        switch($type){

            case 'post':
                $_q = decrypt($request->_q);
                $user = Auth::user();
                $post = Post::findOrFail($_q);
                $report = new Report();
                $report->body = $request->body;
                $report->reportable()->associate($post);
                $user->reports()->save($report);
                $report->save();
                return back()->with('status', 'Your report has been successfully submitted! Thank you for helping us making our website a better place.');
            
            case 'comment':
                $_q = decrypt($request->_q);
                $user = Auth::user();
                $comment = Comment::findOrFail($_q);
                $report = new Report();
                $report->body = $request->body;
                $report->reportable()->associate($comment);
                $user->reports()->save($report);
                $report->save();
                return back()->with('status', 'Your report has been successfully submitted! Thank you for helping us making our website a better place.');    

            default:
                return back()->withErrors('Reported object cannot be found.');    

        }

    }

}
