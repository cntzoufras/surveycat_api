<?php

namespace App\Services\Theme;

use App\Models\Theme\Theme;
use App\Repositories\Theme\ThemeRepository;

class ThemeService
{

    protected ThemeRepository $theme_repository;

    public function __construct(ThemeRepository $theme_repository)
    {
        $this->theme_repository = $theme_repository;
    }

    /**
     * @throws \Exception
     */
    public function index(array $params): mixed
    {
        return $this->theme_repository->index($params);
    }

    /**
     * @param array $params
     *
     * @return \App\Models\Theme\Theme
     */
    public function store(array $params): Theme
    {
        return $this->theme_repository->store($params);
    }

    /**
     * @param $params
     *
     * @return mixed
     */
    public function show($params): mixed
    {
        return $this->theme_repository->resolveModel($params);
    }

    /**
     * @param \App\Models\Theme\Theme $theme
     * @param array $params
     *
     * @return mixed
     */
    public function update(Theme $theme, array $params): mixed
    {
        $theme = $this->theme_repository->resolveModel($theme);
        return $this->theme_repository->update($theme, $params);
    }

    /**
     * @param $theme
     *
     * @return mixed
     */
    public function delete($theme): mixed
    {
        $theme = $this->theme_repository->resolveModel($theme);
        return $this->theme_repository->delete($theme);
    }

}
