<?php
    
    use App\Http\Controllers\Auth\AuthenticationController;
    use App\Http\Controllers\SurveyCategoryController;
    use App\Http\Controllers\SurveyPageController;
    use App\Http\Controllers\SurveyRespondentController;
    use App\Http\Controllers\SurveySubmissionController;
    use App\Http\Controllers\SurveyTemplateController;
    use App\Http\Controllers\QuestionController;
    use App\Http\Controllers\ThemeSettingsController;
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
    Route::prefix('questions')->group(function () {
        Route::get('/', [QuestionController::class, 'index']);
        Route::post('/', [QuestionController::class, 'store']);
        Route::put('/{id}', [QuestionController::class, 'update']);
        Route::get('/{id}', [QuestionController::class, 'show']);
        Route::delete('/{id}', [QuestionController::class, 'delete']);
    });
    Route::prefix('survey-pages')->group(function () {
        Route::get('/', [SurveyPageController::class, 'index']);
        Route::post('/', [SurveyPageController::class, 'store']);
        Route::put('/{id}', [SurveyPageController::class, 'update']);
        Route::get('/{id}', [SurveyPageController::class, 'show']);
        Route::delete('/{id}', [SurveyPageController::class, 'delete']);
    });
    Route::prefix('theme-styles')->group(function () {
        Route::get('/', [ThemeSettingsController::class, 'index']);
        Route::post('/', [ThemeSettingsController::class, 'store']);
        Route::put('/', [ThemeSettingsController::class, 'update']);
        Route::get('/{id}', [ThemeSettingsController::class, 'show']);
        Route::delete('/{id}', [ThemeSettingsController::class, 'delete']);
    });
    Route::prefix('survey-respondents')->group(function () {
        Route::get('/', [SurveyRespondentController::class, 'index']);
        Route::post('/', [SurveyRespondentController::class, 'store']);
        Route::put('/', [SurveyRespondentController::class, 'update']);
        Route::get('/{id}', [SurveyRespondentController::class, 'show']);
        Route::delete('/{id}', [SurveyRespondentController::class, 'delete']);
    });
    
    Route::prefix('survey-categories')->group(function () {
        Route::get('/', [SurveyCategoryController::class, 'index']);
        Route::post('/', [SurveyCategoryController::class, 'store']);
        Route::put('/{id}', [SurveyCategoryController::class, 'update']);
        Route::get('/{id}', [SurveyCategoryController::class, 'show']);
        Route::delete('/{id}', [SurveyCategoryController::class, 'delete']);
    });
    