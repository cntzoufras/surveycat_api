<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyResponse\StoreSurveyResponseRequest;
use App\Models\Survey\SurveyResponse;
use App\Services\Survey\SurveyResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SurveyResponseController extends Controller {

    protected SurveyResponseService $survey_response_service;

    public function __construct(SurveyResponseService $survey_response_service) {
        $this->survey_response_service = $survey_response_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Exception
     */
    public function index(Request $request) {
        $validated = $request->validate(['limit' => 'integer|sometimes|min:0|max:100']);
        return $this->survey_response_service->index($validated);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Exception
     */
    public function store(StoreSurveyResponseRequest $request): SurveyResponse {
        $params = array_merge($request->validated(), ['respondent_ip' => $this->getRespondentIp($request)]);
        return $this->survey_response_service->store($params);
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
                    'id' => 'uuid|required|exists:survey_responses,id',
                ]);
                return $this->survey_response_service->show($request['id']);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
        return null;
    }

    private function getRespondentIp(StoreSurveyResponseRequest $request): ?string {
        return $request->ip();
    }
}
