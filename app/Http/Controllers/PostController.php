<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostDetailResource;

use App\Models\Post;

class PostController extends Controller
{
    //
    public function index(){
        $posts = Post::all();
        // return response()->json(['data'=> $posts]);
        // ngembaliin data array pakai colecction
        return PostResource::collection($posts);
    }


    public function show($id){
        // return response()->json(['data'=> $id]);
        $post = Post::with('writer:id,name')->findOrFail($id);
        return new PostDetailResource($post);
        // return new PostDetailResource($id);
    }

    public function show2($id){
        // return response()->json(['data'=> $id]);
        $post = Post::findOrFail($id);
        return new PostDetailResource($post);
        // return new PostDetailResource($id);
    }
}