<?php
    
    namespace App\Services;
    
    use App\Repositories\RespondentRepository;
    
    class RespondentService {
        
        protected RespondentRepository $survey_respondent_repository;
        
        public function __construct(RespondentRepository $survey_respondent_repository) {
            $this->survey_respondent_repository = $survey_respondent_repository;
        }
        
        public function index(array $params): mixed {
            
            return $this->survey_respondent_repository->index($params);
        }
        
        public function store(array $params): \App\Models\Respondent {
            return $this->survey_respondent_repository->store($params);
        }
        
        public function update($survey_category, array $params): mixed {
            
            $survey_category = $this->survey_respondent_repository->resolveModel($survey_category);
            return $this->survey_respondent_repository->update($survey_category, $params);
        }
        
        public function delete($survey_category): mixed {
            $survey_category = $this->survey_respondent_repository->resolveModel($survey_category);
            return $this->survey_respondent_repository->delete($survey_category);
        }
        
        public function show($params): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\App\Models\Respondent|\Illuminate\Database\Eloquent\Builder|array|null {
            return $this->survey_respondent_repository->resolveModel($params);
        }
        
    }