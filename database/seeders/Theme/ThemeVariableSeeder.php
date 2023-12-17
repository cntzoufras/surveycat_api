<?php

namespace Database\Seeders\Theme;

use App\Models\Theme\Theme;
use App\Models\Theme\ThemeVariable;
use Illuminate\Database\Seeder;

class ThemeVariableSeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void {
        for ($i = 0; $i < 50; $i++) {
            $theme_id = $this->getThemeId();
            $theme_thumb = $this->generateThemeThumb();
            $primary_background_alpha = $this->getRandomBackgroundAlpha();
            ThemeVariable::query()->create(['primary_background_alpha' => $primary_background_alpha,
                                            'theme_thumb'              => $theme_thumb,
                                            'theme_id'                 => $theme_id,
            ]);
        }
    }

    private function getThemeId() {
        return Theme::query()->inRandomOrder()->first()->id;
    }

    private function generateThemeThumb(): string {
        $base_path = 'images/theme_thumbs/';
        $fname = 'thumb_' . md5(uniqid(rand(), true)) . '.jpg';
        return $base_path . $fname;
    }

    private function getRandomBackgroundAlpha(): int {
        return rand(1, 50);
    }
}
