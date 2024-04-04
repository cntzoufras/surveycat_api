<?php

namespace Database\Seeders\Theme;

use App\Models\Theme\Theme;
use App\Models\Theme\ThemeSetting;
use App\Models\Theme\ThemeVariable;
use Illuminate\Database\Seeder;

class ThemeVariableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @throws \Exception
     */
    public function run(): void {

        // Fetch all theme setting IDs
        $theme_settings = ThemeSetting::all();

        // Ensure there are ThemeSettings available
        if ($theme_settings->isEmpty()) {
            throw new \Exception("No ThemeSettings found. Ensure ThemeSettings have been seeded before running this seeder.");
        }

        foreach ($theme_settings as $theme_setting) {
            $theme_thumb = $this->generateThemeThumb();
            $primary_background_alpha = $this->getRandomBackgroundAlpha();

            ThemeVariable::query()->create([
                'primary_background_alpha' => $primary_background_alpha,
                'theme_thumb'              => $theme_thumb,
                'theme_setting_id'         => $theme_setting->id,
            ]);
        }
    }

    private function generateThemeThumb(): string {
        $base_path = 'images/theme_thumbs/';
        $fname = 'thumb_' . md5(uniqid(rand(), true)) . '.jpg';
        return $base_path . $fname;
    }

    private function getRandomBackgroundAlpha(): float {
        return round(mt_rand() / mt_getrandmax(), 2);
    }
}
