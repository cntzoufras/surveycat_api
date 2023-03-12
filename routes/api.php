<?php
    
    use App\Http\Resources\PostCollection;
    use App\Http\Controllers\VesselController;
    use App\Http\Controllers\QuestionController;
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

//    Route::group(['middleware' => ['authentication'], 'namespace' => 'Auth'], function () {
//        Route::post('login', 'AuthenticationController@login');
//        Route::post('logout', 'AuthenticationController@logout');
//        Route::post('register', 'AuthenticationController@register');
//        Route::post('refresh', 'AuthenticationController@refresh');
//        Route::post('forgot-password', 'AuthenticationController@forgotPassword');
//        Route::post('reset-password', 'AuthenticationController@resetPassword');
//    });

//    Route::middleware('auth:api')->get('/user', function (Request $request) {
//        return $request->user();
//    });

// Resources
    Route::get('/posts/{id}', function ($id) {
        return new PostResource(Post::findOrFail($id));
    });
    
    Route::get('/posts', function () {
        return PostResource::collection(Post::all());
    });
    
    Route::get('/posts-less-than-id-ten', function () {
        return new PostResource(Post::query()->where('id', '<', 10));
    });
    
    Route::get('/posts-collection', function () {
        return new PostCollection(Post::paginate());
    });
    
    Route::post('/posts', 'App\Http\Controllers\PostController@store');
    
    Route::prefix('vessels')->group(function () {
        Route::get('/', [VesselController::class, 'index']);
        Route::post('/', [VesselController::class, 'store']);
        Route::get('/{id}', [VesselController::class, 'show']);
    });
    
    Route::prefix('questions')->group(function () {
        Route::get('/', [QuestionController::class, 'index']);
        Route::post('/', [QuestionController::class, 'store']);
        Route::get('/{id}', [QuestionController::class, 'show']);
    });
    
    Route::prefix('survey-pages')->group(function () {
        Route::get('/', [\App\Http\Controllers\SurveyPagesController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\SurveyPagesController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\SurveyPagesController::class, 'show']);
    });
    
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
