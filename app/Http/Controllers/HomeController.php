<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
   

        // 'Designer', 'Customer','Constructor','Admin'
        if ( auth()->user()->role === "Constructor" ) {
            return view('constructor.index');
          

        }
        if ( auth()->user()->role === "Customer" ) {
            return view('customer.index');

        }
        if ( auth()->user()->role === "Designer" ) {
            
            return view('designer.index');

        }

        return view('home');

    }
}
