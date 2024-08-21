<?php

namespace App\Services\Theme;

use App\Models\Theme\VariablePalette;
use App\Repositories\Theme\VariablePaletteRepository;

class VariablePaletteService {

    protected VariablePaletteRepository $variable_palette_repository;

    public function __construct(VariablePaletteRepository $variable_palette_repository) {
        $this->variable_palette_repository = $variable_palette_repository;
    }

    /**
     * @throws \Exception
     */
    public function index(array $params): mixed {
        return $this->variable_palette_repository->index($params);
    }

    /**
     * @param $params
     *
     * @return mixed
     */
    public function show($params): mixed {
        return $this->variable_palette_repository->getIfExist($params);
    }

    /**
     * @param \App\Models\Theme\VariablePalette $variable_palette
     * @param array $params
     *
     * @return mixed
     */
    public function update(VariablePalette $variable_palette, array $params): mixed {
        $variable_palette = $this->variable_palette_repository->resolveModel($variable_palette);
        return $this->variable_palette_repository->update($variable_palette, $params);
    }


}