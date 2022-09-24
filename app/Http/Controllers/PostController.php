<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller {

    public function index() {
        return new PostCollection(Post::all());
    }

    public function show($id) {
        $post = Post::find($id);
        return new PostResource($post);
    }

    public function create(Request $request) {
        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;

        $post->save();
        return response()->json(['title' => $post->title, 'body' => $post->body]);
    }

    public function update(Request $request) {
        $post = Post::find($request->id);
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();
        return back()->with('post_updated', 'Post has been updated successfully!');
    }

    public function deletePost($id) {
        Post::where('id', $id)->delete();
        return back()->with('post_deleted', 'Post has been deleted successfully!');
    }


}
