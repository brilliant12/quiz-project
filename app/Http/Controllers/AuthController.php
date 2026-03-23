<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class AuthController extends Controller
{
    public function login(Request $request)
    { 
        
        if($request->method()=='POST')
        {
            
            $request->validate([
                'email'=>'required|email',
                'password'=>'required'
            ],[
                'email.required'=>'Please Enter Email Id',
                'password.required'=>'Please Enter Password'
            ]);
            if ($request->input('captcha_input') != session('captcha')) {
                return back()->with('error' , 'CAPTCHA is incorrect.');
            }
        }
        return view('auth.login');
    }

    
}
