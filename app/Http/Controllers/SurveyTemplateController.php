<?php
    
    namespace App\Http\Controllers;
    
    use App\Services\SurveyTemplateService;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Resources\Json\JsonResource;
    use App\Repositories\SurveyTemplateRepository;
    
    use Illuminate\Http\Request;
    
    class SurveyTemplateController extends Controller {
        
        protected $survey_template_service;
        private   $repo;
        
        
        public function __construct(SurveyTemplateService $survey_template_service) {
            $this->survey_template_service = $survey_template_service;
        }
        
        public function index(Request $request) {
            $validated = $request->validate(['limit' => 'integer|nullable|min:0|max:50']);
            return $this->survey_template_service->index($validated);
        }
        
        public function show(Request $request) {
            try {
                $id = isset($request->id) ? $request->id : '';
                $validated = $request->validate([
                    'id' => 'integer',
                ]);
                $offset = (int)$request['id'];
                return $this->survey_template_service->show($offset);
            } catch (\Exception $e) {
                throw new \Exception('Error occurred while retrieving survey_templates', 500);
            }
        }
        
        public function store(Request $request) {
            $validated = $request->validate([
                'title'       => 'required|string',
                'description' => 'required|string',
            
            ]);
            return $this->survey_template_service->store($validated);
        }
        
        public function update($setting_id, Request $request) {
            $validated = $request->validate([
                'name'     => 'string|required',
                'settings' => 'required|array',
            ]);
            return $this->survey_template_service->update($setting_id, $validated);
        }
        
        public function destroy($setting_id) {
            return $this->survey_template_service->delete($setting_id);
        }
    }
