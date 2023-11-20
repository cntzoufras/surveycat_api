<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Survey\SurveyQuestionController;
use App\Http\Controllers\RespondentController;
use App\Http\Controllers\Survey\SurveyCategoryController;
use App\Http\Controllers\Survey\SurveyPageController;
use App\Http\Controllers\Survey\SurveyTemplateController;
use App\Http\Controllers\Theme\ThemeSettingsController;
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
Route::prefix('survey-questions')->group(function () {
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
Route::prefix('theme-styles')->group(function () {
    Route::get('/', [ThemeSettingsController::class, 'index']);
    Route::post('/', [ThemeSettingsController::class, 'store']);
    Route::put('/', [ThemeSettingsController::class, 'update']);
    Route::get('/{id}', [ThemeSettingsController::class, 'show']);
    Route::delete('/{id}', [ThemeSettingsController::class, 'delete']);
});
Route::prefix('respondents')->group(function () {
    Route::get('/', [RespondentController::class, 'index']);
    Route::post('/', [RespondentController::class, 'store']);
    Route::put('/', [RespondentController::class, 'update']);
    Route::get('/{id}', [RespondentController::class, 'show']);
    Route::delete('/{id}', [RespondentController::class, 'delete']);
});

Route::prefix('survey-categories')->group(function () {
    Route::get('/', [SurveyCategoryController::class, 'index']);
    Route::post('/', [SurveyCategoryController::class, 'store']);
    Route::put('/{id}', [SurveyCategoryController::class, 'update']);
    Route::get('/{id}', [SurveyCategoryController::class, 'show']);
    Route::delete('/{id}', [SurveyCategoryController::class, 'delete']);
});
    