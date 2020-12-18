<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Report;
use App\Post;
use App\Comment;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Crypt;
use Auth;
use DB;

class AdminReportController extends Controller
{

    /** 
    * Index reports for admins.
    * @param array $reports
    * @return View
    */
    public function adminIndex($reports = null)
    {
        if(!$reports){
            $reports = Report::orderBy('id', 'desc')->paginate(20);
        } else {
            $reports = $reports->paginate(20);
        }
        $report_types = DB::table('reports')->select('reportable_type')->groupBy('reportable_type')->get();
        foreach($report_types as $report_type){
            $report_type->reportable_type = stripslashes(str_replace('App', '', $report_type->reportable_type));
        }
        return view('admin.reports.reports', ['reports' => $reports, 'report_types' => $report_types]);
    }


    /** 
    * Show a report for admins.
    * @param int $id
    * @return View
    */
    public function adminShow($id)
    {
        $report = Report::findOrFail($id);
        return view('admin.reports.show', ['report' => $report]);
    }

    /**
     * Delete the report for admins.
     * @param  int $id
     * @return RedirectResponse
     */
    public function adminDestroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();
        return redirect()->route('admin.index.reports')->with('message', 'The report has been deleted');
    }


    /**
     * Search reports for admins.
     * @param Request
     * @return adminIndex()
     */
    public function adminSearch(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'reportable_id' => 'integer|nullable',
            'reportable_type' => 'string'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect()->route('admin.index.reports')->withErrors($validator)->withInput();
        }

        $id = $request->id;
        $reportable_id = $request->reportable_id;
        $reportable_type = $request->reportable_type;
        $where_arr = array();

        if($id){

            $id_where = ['id', '=', $id];
            array_push($where_arr, $id_where);

        } if($reportable_type){

            $reportable_type_where = ['reportable_type', 'LIKE', '%' . $reportable_type];
            array_push($where_arr, $reportable_type_where);

        } if($reportable_id){

            $reportable_id_where = ['reportable_id', '=', $reportable_id];
            array_push($where_arr, $reportable_id_where);

        }

        $reports = Report::where($where_arr);

        if(empty($reports)){
            return $this->adminIndex();
        }
        return $this->adminIndex($reports);
    }

}
