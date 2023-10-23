<?php
    
    namespace App\Services;
    
    use App\Repositories\SurveyPageRepository;
    
    class SurveyPageService {
        
        protected $survey_page_repository;
        
        public function __construct(SurveyPageRepository $survey_page_repository) {
            $this->survey_page_repository = $survey_page_repository;
        }
        
        public function index(array $params) {
            
            return $this->survey_page_repository->index($params);
        }
        
        public function store(array $params) {
            return $this->survey_page_repository->store($params);
        }
        
        public function update($survey_page, array $params) {
            
            $survey_page = $this->survey_page_repository->resolveModel($survey_page);
            return $this->survey_page_repository->update($survey_page, $params);
        }
        
        public function delete($survey_page) {
            $survey_page = $this->survey_page_repository->resolveModel($survey_page);
            return $this->survey_page_repository->delete($survey_page);
        }
        
        public function show($params) {
            return $this->survey_page_repository->resolveModel($params);
        }
        
    }