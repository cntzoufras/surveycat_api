<?php
    
    namespace App\Http\Controllers;
    
    use App\Services\VesselService;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Resources\Json\JsonResource;
    use App\Repositories\VesselRepository;
    
    use Illuminate\Http\Request;
    
    class VesselController extends Controller {
        
        protected $vessel_service;
        private   $repo;
        
        
        public function __construct(VesselService $vessel_service) {
            $this->vessel_service = $vessel_service;
        }
        
        public function index(Request $request) {
            $validated = $request->validate(['limit' => 'integer|nullable|min:0|max:50']);
            return $this->vessel_service->index($validated);
        }
        
        public function show(Request $request) {
            try {
                $id = isset($request->id) ? $request->id : '';
                $validated = $request->validate([
                    'id' => 'integer',
                ]);
                $offset = (int)$request['id'];
                return $this->vessel_service->show($offset);
            } catch (\Exception $e) {
                throw new \Exception('Error occurred while retrieving vessels', 500);
            }
        }
        
        public function store(Request $request) {
            $validated = $request->validate([
                'title'       => 'required|string',
                'description' => 'required|string',
            
            ]);
            return $this->vessel_service->store($validated);
        }
        
        public function update($setting_id, Request $request) {
            $validated = $request->validate([
                'name'     => 'string|required',
                'settings' => 'required|array',
            ]);
            return $this->vessel_service->update($setting_id, $validated);
        }
        
        public function destroy($setting_id) {
            return $this->vessel_service->delete($setting_id);
        }
    }
