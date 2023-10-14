<?php
    
    use App\Http\Controllers\Auth\AuthenticationController;
    use App\Http\Controllers\SurveyCategoryController;
    use App\Http\Controllers\SurveySubmissionController;
    use App\Http\Resources\PostCollection;
    use App\Http\Controllers\SurveyTemplateController;
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
    Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
        return $request->user();
    });
    Route::controller(AuthenticationController::class)->group(function () {
        
        Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
        Route::post('logout', [AuthenticationController::class, 'logout']);
        Route::post('register', [AuthenticationController::class, 'register']);
        Route::post('refresh', [AuthenticationController::class, 'refresh']);
        Route::post('forgot-password', [AuthenticationController::class, 'forgot-password']);
        Route::post('reset-password', [AuthenticationController::class, 'reset-password']);
    });
    Route::prefix('survey-templates')->group(function () {
        Route::get('/', [SurveyTemplateController::class, 'index']);
        Route::post('/', [SurveyTemplateController::class, 'store']);
        Route::get('/{id}', [SurveyTemplateController::class, 'show']);
    });
    Route::prefix('survey-categories')->group(function () {
        Route::get('/', [SurveyCategoryController::class, 'index']);
        Route::post('/', [SurveyCategoryController::class, 'store']);
        Route::get('/{id}', [SurveyCategoryController::class, 'show']);
        Route::delete('/{id}', [SurveyCategoryController::class, 'delete']);
    });
    Route::prefix('questions')->group(function () {
        Route::get('/', [QuestionController::class, 'index']);
        Route::post('/', [QuestionController::class, 'store']);
        Route::get('/{id}', [QuestionController::class, 'show']);
    });
    Route::prefix('survey-pages')->group(function () {
        Route::get('/', [SurveySubmissionController::class, 'index']);
        Route::post('/', [SurveySubmissionController::class, 'store']);
        Route::get('/{id}', [SurveySubmissionController::class, 'show']);
    });