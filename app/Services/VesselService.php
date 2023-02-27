<?php
    
    namespace App\Services;
    
    use App\Repositories\VesselRepository;

//    use Exceptions\InvalidOperationException;
    
    class VesselService {
        
        protected $vessel_repository;
        
        public function __construct(VesselRepository $vessel_repository) {
            $this->vessel_repository = $vessel_repository;
        }
        
        public function index(array $params) {
            return $this->vessel_repository->index($params);
        }
        
        public function store(array $params) {
            return $this->vessel_repository->store($params);
        }
        
        public function update($vessel_id, array $params) {
            $vessel = $this->vessel_repository->resolveModel($vessel_id);
            return $this->vessel_repository->update($vessel, $params);
        }
        
        public function delete($vessel_id) {
            $vessel = $this->vessel_repository->resolveModel($vessel_id);
            return $this->vessel_repository->delete($vessel);
        }
        
        public function show($params) {
            
            return $this->vessel_repository->getIfExist($params);
        }
        
    }