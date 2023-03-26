<?php
    
    namespace App\Services;
    
    use App\Repositories\SurveyTemplateRepository;

//    use Exceptions\InvalidOperationException;
    
    class SurveyTemplateService {
        
        protected $survey_template_repository;
        
        public function __construct(SurveyTemplateRepository $survey_template_repository) {
            $this->survey_template_repository = $survey_template_repository;
        }
        
        public function index(array $params) {
            return $this->survey_template_repository->index($params);
        }
        
        public function store(array $params) {
            return $this->survey_template_repository->store($params);
        }
        
        public function update($survey_template_id, array $params) {
            $survey_template = $this->survey_template_repository->resolveModel($survey_template_id);
            return $this->survey_template_repository->update($survey_template, $params);
        }
        
        public function delete($survey_template_id) {
            $survey_template = $this->survey_template_repository->resolveModel($survey_template_id);
            return $this->survey_template_repository->delete($survey_template);
        }
        
        public function show($params) {
            
            return $this->survey_template_repository->getIfExist($params);
        }
        
    }