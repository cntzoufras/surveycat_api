<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyQuestion\StoreSurveyQuestionRequest;
use App\Http\Requests\SurveyQuestion\UpdateSurveyQuestionRequest;
use App\Models\QuestionType;
use App\Models\Survey\SurveyPage;
use App\Models\Survey\SurveyQuestion;
use App\Services\Survey\SurveyQuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SurveyQuestionController extends Controller
{

    protected SurveyQuestionService $survey_question_service;

    public function __construct(SurveyQuestionService $survey_question_service)
    {
        $this->survey_question_service = $survey_question_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $validated = $request->validate(['limit' => 'integer|sometimes|min:0|max:100']);
        return $this->survey_question_service->index($validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyQuestionRequest $request): SurveyQuestion
    {
        return $this->survey_question_service->store($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @throws \Exception
     */
    public function show(Request $request): mixed
    {
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
    public function update(UpdateSurveyQuestionRequest $request, SurveyQuestion $survey_question)
    {
        return $this->survey_question_service->update($survey_question, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyQuestion $survey_question)
    {
        return $this->survey_question_service->delete($survey_question);
    }

    public function getSurveyQuestionsByPage($survey_id, $survey_page_id)
    {
        return $this->survey_question_service->getSurveyQuestionsByPage($survey_id, $survey_page_id);
    }

    public function getQuestionTypes(): JsonResponse
    {
        $questionTypes = QuestionType::get();
        return response()->json($questionTypes);
    }

    public function getSurveyQuestionsWithChoices(Request $request, $survey_id): JsonResponse
    {
        try {
            $survey_questions_with_choices = $this->survey_question_service->getSurveyQuestionsWithChoices($survey_id);
            return response()->json($survey_questions_with_choices, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Updates the sort order of questions on a given survey page.
     *
     * @param Request $request
     * @param SurveyPage $survey_page Route model binding gets the page from the URL
     * @return JsonResponse
     */
    public function updateOrder(Request $request, SurveyPage $survey_page): JsonResponse
    {
        $validated = $request->validate([
            'questions' => ['required', 'array'],
            'questions.*.id' => [
                'required',
                'string',
                Rule::exists('survey_questions', 'id'),
            ],
            'questions.*.sort_index' => ['required', 'integer', 'min:0'],
        ]);

        $updatedQuestions = $this->survey_question_service->updateOrder($survey_page, $validated['questions']);

        return response()->json($updatedQuestions);
    }
}
