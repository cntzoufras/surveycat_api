<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyQuestionChoice\StoreSurveyQuestionChoiceRequest;
use App\Http\Requests\SurveyQuestionChoice\UpdateSurveyQuestionChoiceRequest;
use App\Models\Survey\SurveyQuestionChoice;
use App\Services\Survey\SurveyQuestionChoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SurveyQuestionChoiceController extends Controller {

    protected SurveyQuestionChoiceService $survey_question_choice_service;

    public function __construct(SurveyQuestionChoiceService $survey_question_choice_service) {
        $this->survey_question_choice_service = $survey_question_choice_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Exception
     */
    public function index(Request $request) {
        $validated = $request->validate(['limit' => 'integer|sometimes|min:0|max:100']);
        return $this->survey_question_choice_service->index($validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyQuestionChoiceRequest $request): JsonResponse {
        $question_choices = $request->all();
        $createdChoices = $this->survey_question_choice_service->storeMultiple($question_choices);

        return response()->json($createdChoices, 201);
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
                    'id' => 'uuid|required|exists:survey_questions,id',
                ]);
                return $this->survey_question_choice_service->show($request['id']);
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
    public function update(UpdateSurveyQuestionChoiceRequest $request, SurveyQuestionChoice $survey_question_choice) {
        return $this->survey_question_choice_service->update($survey_question_choice, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyQuestionChoice $survey_question) {
        return $this->survey_question_choice_service->delete($survey_question);
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Exception
     */
    public function getSurveyQuestionChoicesByQuestion($question_id) {
        return $this->survey_question_choice_service->getSurveyQuestionChoicesByQuestion($question_id);
    }

}
