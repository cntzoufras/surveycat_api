<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyCategory\StoreSurveyCategoryRequest;
use App\Http\Requests\SurveyCategory\UpdateSurveyCategoryRequest;
use App\Models\Survey\SurveyCategory;
use App\Services\Survey\SurveyCategoryService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveyCategoryController extends Controller {

    protected SurveyCategoryService $survey_category_service;

    public function __construct(SurveyCategoryService $survey_category_service) {
        $this->survey_category_service = $survey_category_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        return $this->survey_category_service->index($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyCategoryRequest $request): SurveyCategory {
        return $this->survey_category_service->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show($id): SurveyCategoryResource|\Illuminate\Http\JsonResponse {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|gt:0|exists:survey_categories,id',
        ]);
        if ($validated->fails()) {
            return response()->json(['message' => 'Validation errors', $validated->errors()], 422);
        }
        return new SurveyCategoryResource($this->survey_category_service->show($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(UpdateSurveyCategoryRequest $request, $id): \Illuminate\Http\JsonResponse {
        $survey_category = $this->survey_category_service->update($id, $request->validated());
        return response()->json(['msg'             => 'success',
                                 'survey_category' => $survey_category,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id): mixed {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|gt:0|exists:survey_categories,id',
        ]);
        if ($validated->fails()) {
            return response()->json(['message' => 'Validation errors', $validated->errors()], 422);
        }
        return $this->survey_category_service->delete($id);
    }
}
