<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DesignerController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        return view('designer.index');
    }

    /**
     * Display the designer's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile(Request $request)
    {
        return view('designer.profile', [
            'user' => $request->user(),
        ]);
    }
}
