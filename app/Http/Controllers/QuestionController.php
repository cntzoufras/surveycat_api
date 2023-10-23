<?php
    
    namespace App\Http\Controllers;
    
    use App\Http\Requests\StoreSurveyQuestionRequest;
    use App\Services\QuestionService;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Resources\Json\JsonResource;
    use App\Repositories\QuestionRepository;
    
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Validation\Rule;
    
    class QuestionController extends Controller {
        
        protected $question_service;
        
        
        public function __construct(QuestionService $question_service) {
            $this->question_service = $question_service;
        }
        
        public function index(Request $request) {
            $validated = $request->validate(['limit' => 'integer|nullable|min:0|max:50']);
            return $this->question_service->index($validated);
        }
        
        public function show(Request $request) {
            try {
                $id = isset($request->id) ? $request->id : '';
                $validated = $request->validate([
                    'id' => 'integer',
                ]);
                $offset = (int)$request['id'];
                return $this->question_service->show($offset);
            } catch (\Exception $e) {
                throw new \Exception('Error occurred while retrieving questions', 500);
            }
        }
        
        public function store(StoreSurveyQuestionRequest $request) {
            $validated = Validator::make($request->all(), [
                'format_id'     => 'integer',
                'is_public'     => 'integer',
                'style_id'      => 'integer',
                'status'        => 'required|string',
                'question_tags' => [
                    'required',
                    'json',
                ],
            ]);
            $errors = $validated->errors();
            if ($validated->fails()) {
                Log::error('Validation errors:', $errors->toArray());
                return response()->json(['errors' => $errors], 422);
            }
            
            return $this->question_service->store($request->all());
        }
        
        public function update($setting_id, Request $request) {
            $validated = $request->validate([
                'name'     => 'string|required',
                'settings' => 'required|array',
            ]);
            return $this->question_service->update($setting_id, $validated);
        }
        
        public function delete($setting_id) {
            return $this->question_service->delete($setting_id);
        }
    }
