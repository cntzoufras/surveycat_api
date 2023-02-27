<?php
    
    namespace App\Repositories;
    
    use App\Models\Vessel;
    
    use Illuminate\Support\Facades\DB;
    
    class VesselRepository {
        
        public function index(array $params) {
            try {
                $limit = isset($params['limit']) ? $params['limit'] : 10;
                return DB::transaction(function () use ($limit) {
                    $vessels = Vessel::paginate($limit);
                    return $vessels;
                });
            } catch (\Exception $e) {
                throw new \Exception('Error occurred while retrieving vessels', 500);
            }
        }
        
        public function resolveModel($vessel_id) {
            if ($vessel_id instanceof Vessel) {
                return $vessel_id;
            }
            return Vessel::query()->findOrFail($vessel_id);
        }
        
        public function getIfExist($vessel_id) {
            return Vessel::query()->find($vessel_id);
        }
        
        public function update(Vessel $vessel, array $params) {
            return DB::transaction(function () use ($params, $vessel) {
                $vessel->fill($params);
                $vessel->save();
                return $vessel;
            });
        }
        
        public function store(array $params): Vessel {
            return DB::transaction(function () use ($params) {
                $vessel = new Vessel();
                $vessel->fill($params);
                $vessel->save();
                return $vessel;
            });
        }
        
        public function delete(Vessel $vessel) {
            return DB::transaction(function () use ($vessel) {
                $vessel->delete();
                return $vessel;
            });
        }
        
    }