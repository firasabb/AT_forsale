<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * View a page.
     *
     * @param string $url
     * @return View
     */

    public function showPage($url){

        $page = new Page();
        $page = $page->getPublishedPageByUrl($url);
        return view('pages.show', ['page' => $page]);

    }

}
