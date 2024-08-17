<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Http\Requests\SurveyQuestion\UpdateSurveyQuestionRequest;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyQuestion;
use App\Services\Survey\SurveyService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    public function update(UpdateSurveyRequest $request, $id) {
        return $this->survey_service->update($id, $request->validated());
    }


    public function publish(UpdateSurveyRequest $request, Survey $survey) {
        return $this->survey_service->publish($survey, $request->validated());
    }

    public function destroy($id) {
        return $this->survey_service->destroy($id);
    }

    /* TODO - Add some method to only allow delete from the user_id that created this survey */

    public function getStockSurveys() {
        return $this->survey_service->getStockSurveys();
    }

}
