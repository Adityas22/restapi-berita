<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    //
    public function store(Request $request){
        // dd($request->all());
        $validateData = $request->validate([
            'post_id' => 'required',
            'comment' => 'required',
        ]);

        $validateData['user_id'] = Auth::id();

        $comment = Comment::create($validateData);

        return new CommentResource($comment->load('commentator:id,name'));
    }

    public function destroy($id){
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda bukan author'], 403);
        }
        $comment->delete();
        return response()->json(['message' => 'Comment deleted']);
    }

    public function update(Request $request, $id){
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Anda bukan author'], 403);
        }
        $data = $request->validate([
            'comment' => 'required',
        ]);
        $comment->update($data);
        return new CommentResource($comment->load('commentator:id,name'));
    }
}