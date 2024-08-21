<?php

namespace App\Repositories\Theme;

use App\Models\Theme\VariablePalette;
use Illuminate\Support\Facades\DB;

class VariablePaletteRepository {

    public function index(array $params) {

        try {
            $limit = $params['limit'] ?? 20;
            return DB::transaction(function () use ($limit) {
                return VariablePalette::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($variable_palette) {
        if ($variable_palette instanceof VariablePalette) {
            return $variable_palette;
        }
        return VariablePalette::query()->findOrFail($variable_palette);
    }

    public function getIfExist($variable_palette) {
        return VariablePalette::query()->find($variable_palette);
    }

    public function update(VariablePalette $variable_palette, array $params) {
        return DB::transaction(function () use ($params, $variable_palette) {
            $variable_palette->fill($params);
            $variable_palette->save();
            return $variable_palette;
        });
    }
}