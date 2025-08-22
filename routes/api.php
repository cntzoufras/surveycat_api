<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\Survey\SurveyCategoryController;
use App\Http\Controllers\Survey\SurveyController;
use App\Http\Controllers\Survey\SurveyQuestionChoiceController;
use App\Http\Controllers\Survey\SurveyQuestionController;
use App\Http\Controllers\RespondentController;
use App\Http\Controllers\Survey\SurveyPageController;
use App\Http\Controllers\Survey\SurveyResponseController;
use App\Http\Controllers\Survey\SurveySubmissionController;
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\Theme\ThemeController;
use App\Http\Controllers\Theme\ThemeSettingsController;
use App\Http\Controllers\Theme\VariablePalettesController;
use App\Http\Controllers\CustomThemeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Dashboards\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [RegisteredUserController::class, 'store']);
Route::get('/surveys/ps/{slug}', [SurveyController::class, 'getPublicSurveyBySlug']);
Route::post('/survey-submissions', [SurveySubmissionController::class, 'store'])->middleware(['invalidate.user.cache']);

Route::get('/question-types', [QuestionTypeController::class, 'index']);;
Route::prefix('survey-responses')->middleware(['invalidate.user.cache'])->group(function () {
    Route::get('/', [SurveyResponseController::class, 'index']);
    Route::post('/', [SurveyResponseController::class, 'store']);
    Route::put('/{survey_response}', [SurveyResponseController::class, 'update']);
    Route::get('/{survey_response}', [SurveyResponseController::class, 'show']);
    Route::patch('/{survey_response}', [SurveyResponseController::class, 'update']);
});
Route::prefix('respondents')->middleware(['cache.user.response','invalidate.user.cache'])->group(function () {
    Route::get('/', [RespondentController::class, 'index']);
    Route::post('/', [RespondentController::class, 'store']);
    Route::put('/{respondent}', [RespondentController::class, 'update']);
    Route::get('/{respondent}', [RespondentController::class, 'show']);
    Route::delete('/{id}', [RespondentController::class, 'delete']);
});
Route::group(['middleware' => [EnsureFrontendRequestsAreStateful::class, 'auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/user', function (Request $request) {
        $user = \Illuminate\Support\Facades\Auth::user();
        return response()->json(['user' => $user]);
    });
    Route::put('/user', [UserController::class, 'update']);
    Route::post('/user/avatar', [UserController::class, 'updateAvatar']);
    Route::get('/global-search', [GlobalSearchController::class, 'search']);
    Route::prefix('survey-questions')->middleware(['invalidate.user.cache'])->group(function () {
        Route::get('/types', [SurveyQuestionController::class, 'getQuestionTypes']);
        Route::get('/', [SurveyQuestionController::class, 'index']);
        Route::post('/', [SurveyQuestionController::class, 'store']);
        Route::put('/{id}', [SurveyQuestionController::class, 'update']);
        Route::get('/{id}', [SurveyQuestionController::class, 'show']);
        Route::delete('{survey_question}', [SurveyQuestionController::class, 'destroy']);
    });

    Route::prefix('survey-pages')->middleware(['cache.user.response','invalidate.user.cache'])->group(function () {
        Route::get('/', [SurveyPageController::class, 'index']);
        Route::post('/', [SurveyPageController::class, 'store']);
        Route::post('/{survey_page}/questions/reorder', [SurveyQuestionController::class, 'updateOrder']);
        Route::put('/{id}', [SurveyPageController::class, 'update']);
        Route::get('/{id}', [SurveyPageController::class, 'show']);
        Route::delete('/{survey_page}', [SurveyPageController::class, 'delete']);
    });

    Route::prefix('surveys')->middleware(['cache.user.response','invalidate.user.cache'])->group(function () {
        Route::get('/count', [SurveyController::class, 'getProfileSurveyCountsForUser']);
        Route::get('/user', [SurveyController::class, 'getSurveysForUser']);

        Route::get('/all', [SurveyController::class, 'getSurveysWithThemesAndPages']);
        Route::get('/{id}/details', [SurveyController::class, 'getSurveyWithDetails']);

        Route::get('/', [SurveyController::class, 'index']);
        Route::post('/', [SurveyController::class, 'store']);
        Route::put('/{survey}', [SurveyController::class, 'update']);
        Route::put('{survey}/publish', [SurveyController::class, 'publish']);
        Route::put('{survey}/preview', [SurveyController::class, 'preview']);
        Route::get('/{id}', [SurveyController::class, 'show']);
        Route::delete('/{id}', [SurveyController::class, 'destroy']);

        Route::get('/{surveyId}/pages', [SurveyPageController::class, 'getSurveyPagesBySurvey']);
        Route::get('/{surveyId}/pages/{surveyPageId}/questions', [
            SurveyQuestionController::class, 'getSurveyQuestionsByPage',
        ]);
        Route::get('/{survey_id}/questions-with-choices', [SurveyQuestionController::class,
            'getSurveyQuestionsWithChoices',
        ]);
    });

    Route::prefix('survey-categories')->group(function () {
        Route::get('/', [SurveyCategoryController::class, 'index']);
    });

    Route::prefix('survey-question-choices')->middleware(['invalidate.user.cache'])->group(function () {
        Route::get('/', [SurveyQuestionChoiceController::class, 'index']);
        Route::post('/', [SurveyQuestionChoiceController::class, 'store']);
        Route::put('/{id}', [SurveyQuestionChoiceController::class, 'update']);
        Route::get('/{id}', [SurveyQuestionChoiceController::class, 'show']);
        Route::delete('/{id}', [SurveyQuestionChoiceController::class, 'destroy']);
        Route::get('/question/{questionId}', [SurveyQuestionChoiceController::class,
            'getSurveyQuestionChoicesByQuestion',
        ]);
    });

    Route::prefix('survey-submissions')->middleware(['cache.user.response','invalidate.user.cache'])->group(function () {
        Route::get('/', [SurveySubmissionController::class, 'index']);
        Route::get('/{id}', [SurveySubmissionController::class, 'show']);
    });

    Route::prefix('themes')->middleware(['cache.user.response','invalidate.user.cache'])->group(function () {
        Route::get('/', [ThemeController::class, 'index']);
        Route::get('/{theme}', [ThemeController::class, 'show']);
        Route::post('/', [ThemeController::class, 'store']);
        Route::put('/{theme}', [ThemeController::class, 'update']);
        Route::delete('/{theme}', [ThemeController::class, 'destroy']);

        // Custom theme routes
        Route::prefix('custom')->group(function () {
            Route::post('/', [CustomThemeController::class, 'store']);
            Route::get('/survey/{survey}', [CustomThemeController::class, 'showBySurvey']);
            Route::put('/{theme}', [CustomThemeController::class, 'update']);
            Route::delete('/{theme}', [CustomThemeController::class, 'destroy']);
            Route::post('/reset/{survey}', [CustomThemeController::class, 'resetToBaseTheme']);
        });

        Route::prefix('settings')->group(function () {
            Route::get('/', [ThemeSettingsController::class, 'index']);
            Route::get('/{theme_setting}', [ThemeSettingsController::class, 'show']);
            Route::post('/', [ThemeSettingsController::class, 'store']);
            Route::put('/{theme_setting}', [ThemeSettingsController::class, 'update']);
            Route::delete('/{theme_setting}', [ThemeSettingsController::class, 'destroy']);
        });
        Route::prefix('variable-palettes')->group(function () {
            Route::get('/', [VariablePalettesController::class, 'index']);
            Route::get('/{variable_palette}', [VariablePalettesController::class, 'show']);
            Route::post('/', [VariablePalettesController::class, 'store']);
            Route::put('/{variable_palette}', [VariablePalettesController::class, 'update']);
            Route::delete('/{variable_palette}', [VariablePalettesController::class, 'destroy']);
        });
    });

    Route::prefix('dashboards')->middleware(['cache.user.response','invalidate.user.cache'])->group(function () {
        Route::get('/app', [DashboardController::class, 'getAppDashboardStats']);
        Route::get('/surveys', [DashboardController::class, 'getSurveyDashboardStats']);
    });

    Route::prefix('users')->group(function () {
        Route::put('/', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'delete']);
    });
});
