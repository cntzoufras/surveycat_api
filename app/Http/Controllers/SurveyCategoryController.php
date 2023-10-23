<?php
    
    namespace App\Http\Controllers;
    
    use AllowDynamicProperties;
    use App\Http\Requests\IndexSurveyCategoryRequest;
    use App\Http\Requests\StoreSurveyCategoryRequest;
    use App\Http\Requests\UpdateSurveyCategoryRequest;
    
    use App\Http\Resources\SurveyCategoryResource;
    use App\Services\SurveyCategoryService;
    use Illuminate\Support\Facades\Validator;
    
    #[AllowDynamicProperties] class SurveyCategoryController extends Controller {
        
        public function __construct(SurveyCategoryService $survey_category_service) {
            $this->survey_category_service = $survey_category_service;
        }
        
        /**
         * Display a listing of the resource.
         */
        public function index(IndexSurveyCategoryRequest $request) {
            $validated = $request->validated();
            
            return $this->survey_category_service->index($validated);
        }
        
        /**
         * Store a newly created resource in storage.
         */
        public function store(StoreSurveyCategoryRequest $request): \App\Models\SurveyCategory {
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
                return response()->json(['error' => 'Invalid ID or Survey Category does not exist'], 400);
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
         * @param \App\Models\SurveyCategory $survey_category
         *
         * @return mixed
         */
        public function delete($id): mixed {
            return $this->survey_category_service->delete($id);
        }
    }
