<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function login(Request $req){
        $this->validate($req, [
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:4',
        ]);
        
        $user = User::where('email' , $req->email)->first();
        if(Hash::check($req->password, $user->password)){
            $user->api_token = Str::random(60);
            $user->save();
            return $this->responseJson(['user' => $user]);
            // return response()->json(['user' => $user]);
        }
        return $this->responseJson(['success' => false, 'message' => 'Password invalid']);
    }

    public function register(Request $req){
        $this->validate($req, [
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:4|confirmed',
        ]);

        if(User::where('email' , $req->email)->select('id')->first()) return $this->responseJson(['success' => false , 'message' => 'Email already exists']);

        $user = User::create([
            'name' => $req->name,
            'username' => $req->username,
            'email'   => $req->email,
            'password' => Hash::make($req->password),
            'api_token' => Str::random(60)
        ]);

        return $this->responseJson($user ?: ['success' => false]);
    }
}