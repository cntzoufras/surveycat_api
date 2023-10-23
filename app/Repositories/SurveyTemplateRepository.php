<?php
    
    namespace App\Repositories;
    
    use App\Models\SurveyTemplate;
    
    use Illuminate\Support\Facades\DB;
    
    class SurveyTemplateRepository {
        
        public function index(array $params) {
            try {
                $limit = $params['limit'] ?? 10;
                return DB::transaction(function () use ($limit) {
                    $survey_templates = SurveyTemplate::paginate($limit);
                    return $survey_templates;
                });
            } catch (\Exception $e) {
                throw new \Exception('Error occurred while retrieving survey_templates', 500);
            }
        }
        
        public function resolveModel($survey_template_id) {
            if ($survey_template_id instanceof SurveyTemplate) {
                return $survey_template_id;
            }
            return SurveyTemplate::query()->findOrFail($survey_template_id);
        }
        
        public function getIfExist($survey_template_id) {
            return SurveyTemplate::query()->find($survey_template_id);
        }
        
        public function update(SurveyTemplate $survey_template, array $params) {
            return DB::transaction(function () use ($params, $survey_template) {
                $survey_template->fill($params);
                $survey_template->save();
                return $survey_template;
            });
        }
        
        public function store(array $params): SurveyTemplate {
            return DB::transaction(function () use ($params) {
                $survey_template = new SurveyTemplate();
                $survey_template->fill($params);
                $survey_template->save();
                return $survey_template;
            });
        }
        
        public function delete(SurveyTemplate $survey_template) {
            return DB::transaction(function () use ($survey_template) {
                $survey_template->delete();
                return $survey_template;
            });
        }
        
    }