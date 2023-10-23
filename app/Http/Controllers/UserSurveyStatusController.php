<?php
    
    namespace App\Http\Controllers;
    
    use App\Http\Requests\StoreUserSurveyStatusRequest;
    use App\Http\Requests\UpdateUserSurveyStatusRequest;
    use App\Models\UserSurveyStatus;
    
    class UserSurveyStatusController extends Controller {
        
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
        public function store(StoreUserSurveyStatusRequest $request) {
            //
        }
        
        /**
         * Display the specified resource.
         */
        public function show(UserSurveyStatus $userStatus) {
            //
        }
        
        /**
         * Show the form for editing the specified resource.
         */
        public function edit(UserSurveyStatus $userStatus) {
            //
        }
        
        /**
         * Update the specified resource in storage.
         */
        public function update(UpdateUserSurveyStatusRequest $request, UserSurveyStatus $userStatus) {
            //
        }
        
        /**
         * Remove the specified resource from storage.
         */
        public function destroy(UserSurveyStatus $userStatus) {
            //
        }
    }
