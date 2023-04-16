<?php
    
    namespace App\Services;
    
    use App\Repositories\SurveySubmissionRepository;
    
    class SurveySubmissionService {
        
        protected $survey_submissions_repository;
        
        public function __construct(SurveySubmissionRepository $survey_submissions_repository) {
            $this->survey_submissions_repository = $survey_submissions_repository;
        }
        
        public function index(array $params) {
            return $this->survey_submissions_repository->index($params);
        }
        
        public function store(array $params) {
            return $this->survey_submissions_repository->store($params);
        }
        
        public function update($question_id, array $params) {
            $question = $this->survey_submissions_repository->resolveModel($question_id);
            return $this->survey_submissions_repository->update($question, $params);
        }
        
        public function delete($question_id) {
            $question = $this->survey_submissions_repository->resolveModel($question_id);
            return $this->survey_submissions_repository->delete($question);
        }
        
        public function show($params) {
            return $this->survey_submissions_repository->getIfExist($params);
        }
        
    }