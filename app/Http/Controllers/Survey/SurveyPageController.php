<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyPage\StoreSurveyPageRequest;
use App\Http\Requests\SurveyPage\UpdateSurveyPageRequest;
use App\Models\Survey\SurveyPage;
use App\Services\Survey\SurveyPageService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SurveyPageController extends Controller {

    protected SurveyPageService $survey_page_service;

    public function __construct(SurveyPageService $survey_page_service) {

        $this->survey_page_service = $survey_page_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Exception
     */
    public function index(Request $request) {
        $validated = $request->validate(['limit' => 'integer|sometimes|min:0|max:100']);
        return $this->survey_page_service->index($validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyPageRequest $request): SurveyPage {
        return $this->survey_page_service->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): mixed {
        try {
            if (isset($request['id'])) {
                Validator::validate(['id' => $request['id']], [
                    'id' => 'uuid|required|exists:survey_pages,id',
                ]);
                return $this->survey_page_service->show($request['id']);
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
     */
    public function update(UpdateSurveyPageRequest $request, $id) {
        return $this->survey_page_service->update($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(SurveyPage $survey_page) {
        return $this->survey_page_service->delete($survey_page);
    }

    public function getSurveyPagesBySurvey($surveyId) {
        try {
            // Validate the incoming request
            Validator::validate(['survey_id' => $surveyId], [
                'survey_id' => 'uuid|required|exists:surveys,id',
            ]);

            // Call the service to fetch survey pages
            return $this->survey_page_service->getSurveyPagesBySurvey($surveyId);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
