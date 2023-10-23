<?php
    
    namespace App\Http\Controllers;
    
    use AllowDynamicProperties;
    use App\Http\Requests\IndexSurveyRespondentRequest;
    use App\Http\Requests\StoreSurveyRespondentRequest;
    use App\Http\Requests\UpdateSurveyRespondentRequest;
    
    use App\Http\Resources\SurveyRespondentResource;
    use App\Services\SurveyRespondentService;
    use Illuminate\Support\Facades\Validator;
    
    #[AllowDynamicProperties] class SurveyRespondentController extends Controller {
        
        public function __construct(SurveyRespondentService $survey_respondent_service) {
            $this->survey_respondent_service = $survey_respondent_service;
        }
        
        
        /**
         * Display a listing of the resource.
         */
        public function index(IndexSurveyRespondentRequest $request) {
            $validated = $request->validated();
            
            return $this->survey_respondent_service->index($validated);
        }
        
        /**
         * Store a newly created resource in storage.
         */
        public function store(StoreSurveyRespondentRequest $request): \App\Models\SurveyRespondent {
            return $this->survey_respondent_service->store($request->validated());
        }
        
        /**
         * Display the specified resource.
         */
        public function show($id): SurveyRespondentResource|\Illuminate\Http\JsonResponse {
            
            $validated = Validator::make(['id' => $id], [
                'id' => 'required|integer|gt:0|exists:survey_categories,id',
            ]);
            
            if ($validated->fails()) {
                return response()->json(['error' => 'Invalid ID or Survey Category does not exist'], 400);
            }
            
            return new SurveyRespondentResource($this->survey_respondent_service->show($id));
        }
        
        /**
         * Show the form for editing the specified resource.
         */
        public function update(UpdateSurveyRespondentRequest $request, $id): \Illuminate\Http\JsonResponse {
            $survey_respondent = $this->survey_respondent_service->update($id, $request->validated());
            return response()->json(['msg'             => 'success',
                                     'survey_category' => $survey_respondent,
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
            return $this->survey_respondent_service->delete($id);
        }
    }
