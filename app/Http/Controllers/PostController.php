<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostDetailResource;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;

class PostController extends Controller
{
    //
    public function index(){
        $posts = Post::all();
        // return response()->json(['data'=> $posts]);
        // ngembaliin data array pakai colecction
        return PostDetailResource::collection($posts->loadMissing('writer:id,name', 'comments.commentator:id,name'));
    }


    public function show($id){
        // return response()->json(['data'=> $id]);
        $post = Post::with('writer:id,name','comments.commentator:id,name')->findOrFail($id);
        return new PostDetailResource($post);
        // return new PostDetailResource($id);
    }

    // public function show2($id){
    //     $post = Post::findOrFail($id);
    //     return new PostDetailResource($post);
    // }

    public function store(Request $request){
        // return $request->file;
        $validateData = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Simpan ke folder public/post-images
            $validateData['image'] = $request->file('image')->store('post-images', 'public');
        }
        
        // $request['author'] = Auth::user()->id;
        // $post = Post::create($request->all());
        // Simpan ID user login
        $validateData['author'] = Auth::id();
        // Simpan ke database menggunakan data yang sudah divalidasi
        $post = Post::create($validateData);
        return new PostDetailResource($post->load('writer:id,name'));
        // return response()->json('berhasil');
    }

    public function update(Request $request, $id){
        if ($request->isMethod('patch')) {
            $request->merge(json_decode($request->getContent(), true));
        }

        $post = Post::findOrFail($id);

        if ($post->author !== Auth::id()) {
            return response()->json(['message' => 'Anda bukan author'], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        if (!$data) {
            return response()->json(['message' => 'No fields provided for update.'], 422);
        }

        $post->update($data);

        return new PostDetailResource($post->load('writer:id,name'));
    }

    public function destroy($id){
        $post = Post::findOrFail($id);
        if ($post->author !== Auth::id()) {
            return response()->json(['message' => 'Anda bukan author'], 403);
        }
        $post->delete();
        return response()->json(['message' => 'Post deleted']);
    }
}