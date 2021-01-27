<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['guest']);
    }
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|same:password'
        ]); //Error-nerna stugum

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            // 'password' => $request->password,
            'password' => Hash::make($request->password),
            
        ]);
        
        // dd(auth()->attempt($request->only('email', 'password')));
        // auth()->attempt($request->only('email', 'password'));
        // dd(auth()->user());

        // return redirect()->route('dashboard');
        return ['messages'=> true];
    }
}
