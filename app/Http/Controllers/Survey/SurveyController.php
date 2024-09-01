<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Models\Survey\Survey;
use App\Services\Survey\SurveyService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SurveyController extends Controller {

    protected SurveyService $survey_service;

    public function __construct(SurveyService $survey_service) {
        $this->survey_service = $survey_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Exception
     */
    public function index(Request $request) {
        $validated = $request->validate(['limit' => 'integer|sometimes|min:0|max:100']);
        return $this->survey_service->index($validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyRequest $request): Survey {
        return $this->survey_service->store($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @throws \Exception
     */
    public function show(Request $request): mixed {
        try {
            if (isset($request['id'])) {
                Validator::validate(['id' => $request['id']], [
                    'id' => 'uuid|required|exists:surveys,id',
                ]);
                return $this->survey_service->show($request['id']);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
        return null;
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Exception
     */
    public function update(UpdateSurveyRequest $request, Survey $survey) {
        return $this->survey_service->update($survey, $request->validated());
    }


    public function publish(UpdateSurveyRequest $request, Survey $survey) {
        return $this->survey_service->publish($survey->id, $request->validated());
    }

    public function destroy($id) {

        try {
            $survey = Survey::findOrFail($id);

            if ($survey->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            }

            return $this->survey_service->destroy($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Survey not found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getStockSurveys() {
        return $this->survey_service->getStockSurveys();
    }

    /**
     * Get the surveys of the authenticated user.
     *
     * @return JsonResponse
     */
    public function getSurveysForUser(): JsonResponse {

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $surveys = $this->survey_service->getSurveysForUser($user->id);
        return response()->json($surveys);
    }

    public function getSurveysWithThemesAndPages(): JsonResponse {
        $surveys = $this->survey_service->getSurveysWithThemesAndPages();
        return response()->json($surveys);
    }

    public function getSurveyWithDetails(Request $request) {
        try {
            if (isset($request['id'])) {
                Validator::validate(['id' => $request['id']], [
                    'id' => 'uuid|required|exists:surveys,id',
                ]);
                return $this->survey_service->getSurveyWithDetails($request['id']);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
        return null;
    }

    public function getPublicSurveyBySlug($slug) {
        try {
            $validator = Validator::make(['slug' => $slug], [
                'slug' => 'string|required|exists:surveys,public_link',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return $this->survey_service->getPublicSurveyBySlug($slug);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


}
