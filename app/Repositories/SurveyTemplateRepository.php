<?php
    
    namespace App\Repositories;
    
    use App\Models\SurveyTemplate;
    
    use Illuminate\Support\Facades\DB;
    
    class VesselRepository {
        
        public function index(array $params) {
            try {
                $limit = isset($params['limit']) ? $params['limit'] : 10;
                return DB::transaction(function () use ($limit) {
                    $vessels = SurveyTemplate::paginate($limit);
                    return $vessels;
                });
            } catch (\Exception $e) {
                throw new \Exception('Error occurred while retrieving vessels', 500);
            }
        }
        
        public function resolveModel($vessel_id) {
            if ($vessel_id instanceof SurveyTemplate) {
                return $vessel_id;
            }
            return SurveyTemplate::query()->findOrFail($vessel_id);
        }
        
        public function getIfExist($vessel_id) {
            return SurveyTemplate::query()->find($vessel_id);
        }
        
        public function update(SurveyTemplate $vessel, array $params) {
            return DB::transaction(function () use ($params, $vessel) {
                $vessel->fill($params);
                $vessel->save();
                return $vessel;
            });
        }
        
        public function store(array $params): SurveyTemplate {
            return DB::transaction(function () use ($params) {
                $vessel = new SurveyTemplate();
                $vessel->fill($params);
                $vessel->save();
                return $vessel;
            });
        }
        
        public function delete(SurveyTemplate $vessel) {
            return DB::transaction(function () use ($vessel) {
                $vessel->delete();
                return $vessel;
            });
        }
        
    }