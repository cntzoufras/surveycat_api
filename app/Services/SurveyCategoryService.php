<?php
    
    namespace App\Services;
    
    use App\Repositories\SurveyCategoryRepository;
    
    class SurveyCategoryService {
        
        protected $survey_category_repository;
        
        public function __construct(SurveyCategoryRepository $survey_category_repository) {
            $this->survey_category_repository = $survey_category_repository;
        }
        
        public function index(array $params) {
            
            return $this->survey_category_repository->index($params);
        }
        
        public function store(array $params) {
            return $this->survey_category_repository->store($params);
        }
        
        public function update($survey_category, array $params) {
            
            $survey_category = $this->survey_category_repository->resolveModel($survey_category);
            return $this->survey_category_repository->update($survey_category, $params);
        }
        
        public function delete($survey_category) {
            $survey_category = $this->survey_category_repository->resolveModel($survey_category);
            return $this->survey_category_repository->delete($survey_category);
        }
        
        public function show($params) {
            return $this->survey_category_repository->resolveModel($params);
        }
        
    }