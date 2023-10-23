<?php
    
    namespace App\Repositories;
    
    use App\Models\ThemeSetting;
    
    use Illuminate\Support\Facades\DB;
    
    class ThemeSettingRepository {
        
        public function index(array $params) {
            
            try {
                $limit = $params['limit'] ?? 10;
                return DB::transaction(function () use ($limit) {
                    return ThemeSetting::query()->paginate($limit);
                });
            } catch (\Exception $e) {
                throw new \Exception($e, 500);
            }
        }
        
        public function resolveModel($theme_setting) {
            if ($theme_setting instanceof ThemeSetting) {
                return $theme_setting;
            }
            return ThemeSetting::query()->findOrFail($theme_setting);
        }
        
        public function getIfExist($theme_setting) {
            return ThemeSetting::query()->find($theme_setting);
        }
        
        public function update(ThemeSetting $theme_setting, array $params) {
            return DB::transaction(function () use ($params, $theme_setting) {
                $theme_setting->fill($params);
                $theme_setting->save();
                return $theme_setting;
            });
        }
        
        public function store(array $params): ThemeSetting {
            return DB::transaction(function () use ($params) {
                $theme_setting = new ThemeSetting();
                $theme_setting->fill($params);
                $theme_setting->save();
                return $theme_setting;
            });
        }
        
        public function delete(ThemeSetting $theme_setting) {
            return DB::transaction(function () use ($theme_setting) {
                $theme_setting->delete();
                return $theme_setting;
            });
        }
        
    }