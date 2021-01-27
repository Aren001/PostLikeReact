<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        // dd(auth()->user()->post);
        // dd(Post::find(4));

        return view('dashboard');
    }
}
