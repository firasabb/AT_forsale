<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;

interface AdminInterface{

    /**
     * Index For Admin
     */
    public function adminIndex($order = '', $desc = false, $model = null);

    /**
     * Show Object For Admin
     */
    public function adminShow($id);

    /**
      * Add a Category For Admins
      * @param Request $request
      * @return RedirectResponse
      *
      */
    //public function adminAdd(Request $request);

    /**
     * Delete Object For Admin
     */
    public function adminDestroy($id);

    /**
     * Edit Object For Admin
     */
    public function adminEdit(Request $request, $id);

    /**
     * Search For Admin
     */
    public function adminSearch(Request $request);

}