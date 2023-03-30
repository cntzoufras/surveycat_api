<?php
    
    namespace App\Http\Controllers;
    
    use App\Http\Requests\StoreSurveyPagesRequest;
    use App\Http\Requests\UpdateSurveyPagesRequest;
    use App\Http\Resources\SurveyPagesResource;
    use App\Models\SurveyPage;
    use App\Services\SurveyPageService;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    
    class SurveyPageController extends Controller {
        
        protected $survey_pages_service;
        
        public function __construct(SurveyPageService $survey_pages_service) {
            $this->survey_pages_service = $survey_pages_service;
        }
        
        /**
         * Display a listing of the resource.
         */
        public function index(Request $request) {
            return SurveyPagesResource::collection($this->survey_pages_service->index($request->all()));
        }
        
        /**
         * Show the form for creating a new resource.
         */
        public function create() {
            //
        }
        
        /**
         * Store a newly created resource in storage.
         */
        public function store(StoreSurveyPagesRequest $request) {
            return SurveyPagesResource::collection($this->survey_pages_service->store($request->validated()));
        }
        
        /**
         * Display the specified resource.
         */
        public function show($id) {
            return $this->survey_pages_service->show($id);
        }
        
        /**
         * Show the form for editing the specified resource.
         */
        public function edit(SurveyPage $surveyPages) {
            //
        }
        
        /**
         * Update the specified resource in storage.
         */
        public function update(SurveyPage $surveyPages, UpdateSurveyPagesRequest $request,) {
            $validated = $request->validate(['pages' => 'string']);
            return SurveyPagesResource::make($this->survey_pages_service->update($surveyPages, $request->validated()));
        }
        
        /**
         * Remove the specified resource from storage.
         */
        public function destroy(SurveyPage $surveyPages) {
            //
        }
    }
