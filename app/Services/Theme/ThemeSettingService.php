<?php

namespace App\Services\Theme;

use App\Repositories\Theme\ThemeSettingRepository;

class ThemeSettingService {

    protected ThemeSettingRepository $theme_setting_repository;

    public function __construct(ThemeSettingRepository $theme_setting_repository) {
        $this->theme_setting_repository = $theme_setting_repository;
    }

    public function index(array $params) {

        return $this->theme_setting_repository->index($params);
    }

    public function store(array $params) {
        return $this->theme_setting_repository->store($params);
    }

    public function update($theme_setting, array $params) {

        $theme_setting = $this->theme_setting_repository->resolveModel($theme_setting);
        return $this->theme_setting_repository->update($theme_setting, $params);
    }

    public function delete($theme_setting) {
        $theme_setting = $this->theme_setting_repository->resolveModel($theme_setting);
        return $this->theme_setting_repository->delete($theme_setting);
    }

    public function show($params) {
        return $this->theme_setting_repository->resolveModel($params);
    }

}