<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyQuestion\StoreSurveyQuestionRequest;
use App\Http\Requests\SurveyQuestion\UpdateSurveyQuestionRequest;
use App\Models\Survey\SurveyQuestion;
use App\Services\SurveyQuestionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SurveyQuestionController extends Controller {

    protected SurveyQuestionService $survey_question_service;

    public function __construct(SurveyQuestionService $survey_question_service) {
        $this->survey_question_service = $survey_question_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(UpdateSurveyQuestionRequest $survey_question_request) {
        $validated = $survey_question_request->validate(['limit' => 'integer|nullable|min:0|max:50']);
        return $this->survey_question_service->index($validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyQuestionRequest $request): \Illuminate\Http\JsonResponse|SurveyQuestion {
        $validated = Validator::make($request->all(), [
            'title'          => 'string|lte:255',
            'is_required'    => 'integer|digits_between:0,1|min:0|max:1',
            'theme_style_id' => 'integer|exists:theme_styles',
            'question_tags'  => [
                'required',
                'json',
            ],
        ]);
        $errors = $validated->errors();
        if ($validated->fails()) {
            Log::error('Validation errors:', $errors->toArray());
            return response()->json(['errors' => $errors], 422);
        }

        return $this->survey_question_service->store($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(UpdateSurveyQuestionRequest $survey_question_request) {
        try {
            $id = $request->id ?? '';
            $validated = $request->validate([
                'id' => 'integer',
            ]);
            $offset = (int)$request['id'];
            return $this->survey_question_service->show($offset);
        } catch (\Exception $e) {
            throw new \Exception('Error occurred while retrieving questions', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveyQuestion $surveyQuestion) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyQuestionRequest $request, SurveyQuestion $survey_question) {
        $validated = $request->validate([
            'name'     => 'string|required',
            'settings' => 'required|array',
        ]);
        return $this->survey_question_service->update($survey_question, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyQuestion $survey_question) {
        return $this->survey_question_service->delete($survey_question);
    }
}
