<?php

namespace App\Http\Controllers;

use App\Mail\PostLiked;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostLikeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    public function store($id , Request $request){
        // dd($post); //et ID Post-y vercnuma

        // if ($post->likedBy($request->user())) {
        //     return response(null, 409);
        // }
        $like = Like::create([
            'post_id'=>$id,
            'user_id' => $request->user_id,
        ]);
        // Mail::to($post->user)->send(new PostLiked(auth()->user(),$post));  //Mail
        // return back();
       
        return $like;
 
    }
    public function destroy(Post $post, Request $request)
    {
        $request->user()->likes()->where('post_id', $post->id)->delete();

        return back();
    }
    public function deleteLike($id , Request $req){
        $del = Like::where('post_id', $id)->delete();
        return $del;
    }
}
