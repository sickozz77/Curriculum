<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function __construct()
    {
    	/* $this->middleware('auth')->only([ '' ]);*/
    }

    public function quemsomos()
    {
        return view('Site/quemsomos');
    }

    public function curriculum($param)
    {
        return view("Site/Curriculum/{$param}");
    }

    public function construindo()
    {
        return redirect()->route('construindo') ;
    }

}
