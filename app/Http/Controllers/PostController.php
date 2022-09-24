<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function addPost() {
    	return view('add-post');
    }

    public function createPost(Request $request) {
    	$post 			= new Post();
    	$post->title 	= $request->title;
    	$post->body 	= $request->body;
    	$post->save();
    	return response()->json(['title' => $post->title,'body'=>$post->body]);
    }

    public function getPost() {
    	$posts = Post::orderBy('id','DESC')->get();
    	return view('posts', compact('posts'));
    }

    public function getPostById($id) {
    	$post = Post::where('id', $id)->first();
        return response()->json(['post' => $post]);
    }

    public function getPosts() {
//        $posts = Post::all();
        $posts = Post::orderBy('created_at', 'desc')->paginate(20);
        return view('posts',compact('posts'));
    }

    public function deletePost($id) {
    	Post::where('id', $id)->delete();
    	return back()->with('post_deleted','Post has been deleted successfully!');
    }

    public function editPost($id) {
    	$post = Post::find($id);
    	return view('edit-post',compact('post'));
    }

    public function updatePost(Request $request) {
    	$post = Post::find($request->id);
    	$post->title = $request->title;
    	$post->body = $request->body;
    	$post->save();
    	return back()->with('post_updated','Post has been updated successfully!');
    }
}
