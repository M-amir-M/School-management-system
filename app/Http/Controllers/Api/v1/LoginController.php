<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validData = $this->validate($request,[
            'username' => 'required|exists:users',
            'password' => 'required'
        ]);

        if(! auth()->attempt($validData)){
            return response([
                'data' => 'اطلاعات ورود درست نیست',
                'status' => 'error'
            ]);
        }

        return response([
            'data' => auth()->user()->api_token,
            'status' => 'success',
            'code' => 200
        ]);
    }

    public function dashboard()
    {

        $userRole = getAuth()->role;
        return view('dashboard.dashboard-user',compact('userRole','years'));
    }
}
