<?php
    
    namespace App\Services;
    
    use App\Repositories\QuestionRepository;

//    use Exceptions\InvalidOperationException;
    
    class QuestionService {
        
        protected $question_repository;
        
        public function __construct(QuestionRepository $question_repository) {
            $this->question_repository = $question_repository;
        }
        
        public function index(array $params) {
            return $this->question_repository->index($params);
        }
        
        public function store(array $params) {
            return $this->question_repository->store($params);
        }
        
        public function update($question_id, array $params) {
            $question = $this->question_repository->resolveModel($question_id);
            return $this->question_repository->update($question, $params);
        }
        
        public function delete($question_id) {
            $question = $this->question_repository->resolveModel($question_id);
            return $this->question_repository->delete($question);
        }
        
        public function show($params) {
            
            return $this->question_repository->getIfExist($params);
        }
        
    }