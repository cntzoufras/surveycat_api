<?php

use App\Http\Controllers\PostController;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
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

// Resources
Route::get('/posts/{id}', function ($id) {
    return new PostResource(Post::findOrFail($id));
});

Route::get('/posts', function () {
    return PostResource::collection(Post::all());
});

Route::get('/posts-collection', function () {
    return new PostCollection(Post::paginate());
});

// --
// Products
Route::get('/products/{product}', function ($id) {
    return response()->json(['productId' => "{$id}"], 201);
});
Route::get('/products', function () {
    return response()->json([
        'message' => 'msg: Products',
    ], 201);
});
Route::put('/products/{product}', function () {
    return response()->json([
        'message' => 'Update success',
    ], 200);
});
Route::delete('/products/{product}', function () {
    return response()->json(null, 204);
});
