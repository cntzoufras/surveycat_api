<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Services\Survey\SurveyService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SurveyTemplateController extends Controller {

    protected SurveyService $survey_service;


    public function __construct(SurveyService $survey_service) {
        $this->survey_service = $survey_service;
    }

    /**
     * @throws \Exception
     */
    public function index(Request $request) {
        $validated = $request->validate(['limit' => 'integer|sometimes|min:0|max:100']);
        return $this->survey_service->getAllTemplates($validated);
    }

    public function show(Request $request) {
        try {
            $id = isset($request->id) ? $request->id : '';
            $validated = $request->validate([
                'id' => 'integer|exists:survey_templates,id',
            ]);
            return $this->survey_service->getTemplate($validated);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            throw new \Exception('Error occurred while retrieving survey_templates', 500);
        }
    }

    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'title'       => 'required|string|max:255',
                'description' => 'nullable|string|max:1e3',
                'survey_id'   => 'required|uuid|exists:surveys,id',
                'user_id'     => 'required|uuid|exists:users,id',
            ]);
            return $this->survey_service->saveAsTemplate($validated);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            throw new \Exception('Error occurred while retrieving survey_templates', 500);
        }
    }

    public function update($survey_template_id, Request $request) {
        $validated = $request->validate([
            'name'     => 'string|required',
            'settings' => 'required|array',
        ]);
        return $this->survey_service->updateTemplate($survey_template_id, $validated);
    }

    public function destroy($survey_template_id) {
        return $this->survey_service->deleteTemplate($survey_template_id);
    }
}
