<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyQuestion\StoreSurveyQuestionRequest;
use App\Http\Requests\SurveyQuestion\UpdateSurveyQuestionRequest;
use App\Models\QuestionType;
use App\Models\Survey\SurveyQuestion;
use App\Services\Survey\SurveyQuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SurveyQuestionController extends Controller {

    protected SurveyQuestionService $survey_question_service;

    public function __construct(SurveyQuestionService $survey_question_service) {
        $this->survey_question_service = $survey_question_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Exception
     */
    public function index(Request $request) {
        $validated = $request->validate(['limit' => 'integer|sometimes|min:0|max:100']);
        return $this->survey_question_service->index($validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyQuestionRequest $request): SurveyQuestion {
        return $this->survey_question_service->store($request->all());
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
                return $this->survey_question_service->show($request['id']);
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
    public function update(UpdateSurveyQuestionRequest $request, SurveyQuestion $survey_question) {
        return $this->survey_question_service->update($survey_question, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyQuestion $survey_question) {
        return $this->survey_question_service->delete($survey_question);
    }

    public function getSurveyQuestionsByPage($survey_id, $survey_page_id) {
        return $this->survey_question_service->getSurveyQuestionsByPage($survey_id, $survey_page_id);
    }

    public function getQuestionTypes() {
        $questionTypes = QuestionType::get();
        return response()->json($questionTypes);
    }


}
