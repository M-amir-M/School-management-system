<?php

namespace App\Http\Controllers;

use App\User;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }
    
    public function dashboard()
    {
        $userRole = getAuth()->role;
        return view('dashboard.dashboard-user',compact('userRole','years'));
    }



}
