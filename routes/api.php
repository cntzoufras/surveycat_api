<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Products
Route::get('products', function () {
    return response(['Product 1', 'Product 2', 'Product 3'],200);
});
Route::get('products/{product}', function ($productId) {
    return response()->json(['productId' => "{$productId}"], 200);
});
Route::post('products', function() {
    return  response()->json([
            'message' => 'Create success'
        ], 201);
});
Route::put('products/{product}', function() {
    return  response()->json([
            'message' => 'Update success'
        ], 200);
});
Route::delete('products/{product}',function() {
    return  response()->json(null, 204);
});

// Posts
Route::get('/add-post', [PostController::class,'addPost']);
Route::post('/create-post',[PostController::class,'createPost'])->name('post.create');
Route::get('/posts/{id}', [PostController::class,'getPostById'])->name('post.get');
Route::get('/delete-post/{id}',[PostController::class,'deletePost'])->name('post.delete');
Route::get('/edit-post/{id}', [PostController::class,'editPost'])->name('post.edit');
Route::post('/update-post',[PostController::class,'updatePost'])->name('post.update');
