<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Survey\SurveyCategoryController;
use App\Http\Controllers\Survey\SurveyController;
use App\Http\Controllers\Survey\SurveyQuestionController;
use App\Http\Controllers\RespondentController;
use App\Http\Controllers\Survey\SurveyPageController;
use App\Http\Controllers\Survey\SurveyResponseController;
use App\Http\Controllers\Survey\SurveySubmissionController;
use App\Http\Controllers\Theme\ThemeController;
use App\Http\Controllers\Theme\ThemeSettingsController;

use App\Http\Controllers\Theme\VariablePalettesController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [RegisteredUserController::class, 'store']);

Route::group(['middleware' => [EnsureFrontendRequestsAreStateful::class, 'auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/user', function (Request $request) {
        $user = \Illuminate\Support\Facades\Auth::user();
        return response()->json(['user' => $user]);
    });

    Route::prefix('survey-questions')->group(function () {
        Route::get('/types', [SurveyQuestionController::class, 'getQuestionTypes']);
        Route::get('/', [SurveyQuestionController::class, 'index']);
        Route::post('/', [SurveyQuestionController::class, 'store']);
        Route::put('/{id}', [SurveyQuestionController::class, 'update']);
        Route::get('/{id}', [SurveyQuestionController::class, 'show']);
        Route::delete('/{id}', [SurveyQuestionController::class, 'delete']);
    });

    Route::prefix('survey-pages')->group(function () {
        Route::get('/', [SurveyPageController::class, 'index']);
        Route::post('/', [SurveyPageController::class, 'store']);
        Route::put('/{id}', [SurveyPageController::class, 'update']);
        Route::get('/{id}', [SurveyPageController::class, 'show']);
        Route::delete('/{id}', [SurveyPageController::class, 'delete']);
    });

    Route::prefix('surveys')->group(function () {
        Route::get('/user', [SurveyController::class, 'getSurveysForUser']);
        Route::get('/stock', [SurveyController::class, 'getStockSurveys']);

        Route::get('/', [SurveyController::class, 'index']);
        Route::post('/', [SurveyController::class, 'store']);
        Route::put('/{id}', [SurveyController::class, 'update']);
        Route::get('/{id}', [SurveyController::class, 'show']);
        Route::delete('/{id}', [SurveyController::class, 'destroy']);

        Route::get('/{surveyId}/pages', [SurveyPageController::class, 'getSurveyPagesBySurvey']);
        Route::get('/{surveyId}/pages/{pageId}/questions', [
            SurveyQuestionController::class, 'getSurveyQuestionsByPage',
        ]);
    });

    Route::prefix('survey-categories')->group(function () {
        Route::get('/', [SurveyCategoryController::class, 'index']);
    });

    Route::prefix('survey-responses')->group(function () {
        Route::get('/', [SurveyResponseController::class, 'index']);
        Route::post('/', [SurveyResponseController::class, 'store']);
        Route::get('/{id}', [SurveyResponseController::class, 'show']);
    });

    Route::prefix('survey-submissions')->group(function () {
        Route::get('/', [SurveySubmissionController::class, 'index']);
        Route::post('/', [SurveySubmissionController::class, 'store']);
        Route::get('/{id}', [SurveySubmissionController::class, 'show']);
    });

    Route::prefix('themes')->group(function () {
        Route::get('/', [ThemeController::class, 'index']);
        Route::post('/', [ThemeController::class, 'store']);
        Route::put('/{id}', [ThemeController::class, 'update']);
        Route::get('/{id}', [ThemeController::class, 'show']);
        Route::delete('/{id}', [ThemeController::class, 'delete']);
    });

    Route::prefix('theme-styles')->group(function () {
        Route::get('/', [ThemeSettingsController::class, 'index']);
        Route::post('/', [ThemeSettingsController::class, 'store']);
        Route::put('/', [ThemeSettingsController::class, 'update']);
        Route::get('/{id}', [ThemeSettingsController::class, 'show']);
        Route::delete('/{id}', [ThemeSettingsController::class, 'delete']);
    });

    Route::prefix('variable-palettes')->group(function () {
        Route::get('/', [VariablePalettesController::class, 'index']);
        Route::post('/', [VariablePalettesController::class, 'store']);
        Route::put('/', [VariablePalettesController::class, 'update']);
        Route::get('/{id}', [VariablePalettesController::class, 'show']);
        Route::delete('/{id}', [VariablePalettesController::class, 'delete']);
    });

    Route::prefix('respondents')->group(function () {
        Route::get('/', [RespondentController::class, 'index']);
        Route::post('/', [RespondentController::class, 'store']);
        Route::put('/', [RespondentController::class, 'update']);
        Route::get('/{id}', [RespondentController::class, 'show']);
        Route::delete('/{id}', [RespondentController::class, 'delete']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'delete']);
    });
//    Route::get('/', 'ListingOptionsController@getOptions');
});