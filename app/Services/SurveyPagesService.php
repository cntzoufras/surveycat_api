<?php
    
    namespace App\Services;
    
    use App\Repositories\SurveyPagesRepository;
    
    class SurveyPagesService {
        
        protected $survey_pages_repository;
        
        public function __construct(SurveyPagesRepository $survey_pages_repository) {
            $this->survey_pages_repository = $survey_pages_repository;
        }
        
        public function index(array $params) {
            return $this->survey_pages_repository->index($params);
        }
        
        public function store(array $params) {
            return $this->survey_pages_repository->store($params);
        }
        
        public function update($question_id, array $params) {
            $question = $this->survey_pages_repository->resolveModel($question_id);
            return $this->survey_pages_repository->update($question, $params);
        }
        
        public function delete($question_id) {
            $question = $this->survey_pages_repository->resolveModel($question_id);
            return $this->survey_pages_repository->delete($question);
        }
        
        public function show($params) {
            
            return $this->survey_pages_repository->getIfExist($params);
        }
        
    }