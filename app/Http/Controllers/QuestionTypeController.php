<?php
    
    namespace App\Http\Controllers;
    
    use App\Http\Requests\StoreQuestionTypeRequest;
    use App\Http\Requests\UpdateQuestionTypeRequest;
    use App\Models\SurveyQuestionType;
    
    class QuestionTypeController extends Controller {
        
        /**
         * Display a listing of the resource.
         */
        public function index() {
            //
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
        public function store(StoreQuestionTypeRequest $request) {
            //
        }
        
        /**
         * Display the specified resource.
         */
        public function show(SurveyQuestionType $questionType) {
            //
        }
        
        /**
         * Show the form for editing the specified resource.
         */
        public function edit(SurveyQuestionType $questionType) {
            //
        }
        
        /**
         * Update the specified resource in storage.
         */
        public function update(UpdateQuestionTypeRequest $request, SurveyQuestionType $questionType) {
            //
        }
        
        /**
         * Remove the specified resource from storage.
         */
        public function destroy(SurveyQuestionType $questionType) {
            //
        }
    }
