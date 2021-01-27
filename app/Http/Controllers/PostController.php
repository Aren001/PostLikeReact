<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $req){
        // $posts = Post::get();
        $user = User::all();
        $posts = Post::orderBy('created_at','desc')->paginate(4);
        // dd($posts);

        // return view('posts.index',[
        //     'posts' => $posts
        // ]);
        return ['auth'=>$req->user(),'postData'=>$posts,'user'=>$user];
    }
    public function store(Request $request)
    {    
        $this->validate($request, [
            'body' => 'required'
        ]);

        //  $request->user()->posts()->create($request->only('body'));

        // return back();
        $post = [
            'user_id'=>$request->user_id,
            'body'=>$request->body,
        ];
        return Post::create($post);  
    }
    public function deletePost($id){ //React
        $post = Post::findOrFail($id);
        $post->delete();
        return 204;
    }
    public function destroy(Post $post)
    {
        // $this->authorize('delete', $post);

        $post->delete();

        return $post->delete();
    }

}
