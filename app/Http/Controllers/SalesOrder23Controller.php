<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesOrder23Controller extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('salesorder23');
    }
}
