<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register()
    {
        $data['title'] = 'Register';
        return view('user/register', $data);
    }

    public function register_action(Request $request)
    {
        $request->validate([
            'name' => 'required' ,
            'username' => 'required|unique:tb_user' ,
            'password' => 'required' ,
            'password_confirmation' => 'required|same:password' ,
        ]);
       $user = new User([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->username),
        ]);
        
        $user->save();
        return redirect()->route('login')->with('success','Registration Succes.Please Login!');
    }

    public function login(Request $request)
    {
        $data['title'] = 'Login';    
        return view('user/login', $data);
    }

    public function login_action(Request $request)
    {
        $request->validate([
            'username' => 'required' ,
            'password' => 'required' ,
        ]);
       if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
        $request->session()->regenerate();
        return redirect()->intended('/');
       }
        return back()->withErrors('password','Wrong username or password!');
    }
}
