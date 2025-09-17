<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //


    public function users (){
        return view('admin.users.index',['users'=> User::where('role','!=','Admin')->paginate(5)]);
    }
}
