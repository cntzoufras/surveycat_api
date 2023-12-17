<?php

namespace Database\Seeders\Theme;

use App\Models\Theme\Theme;
use App\Models\Theme\ThemeSetting;
use Illuminate\Database\Seeder;

class ThemeSettingSeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void {
        $csvPath = base_path('database/seeds/themes_settings.csv');
        $file = fopen($csvPath, 'r');
        fgetcsv($file);
        while (($data = fgetcsv($file)) !== false) {
            $theme_setting = [$data[0]];
            $this->seedThemeSetting($theme_setting);
        }
        fclose($file);
    }

    private function seedThemeSetting(array $theme_setting): void {
        $theme_id = $this->getThemeId();
        ThemeSetting::query()->create(['settings' => json_decode($theme_setting[0], true),
                                       'theme_id' => $theme_id,
        ]);
    }

    private function getThemeId() {
        return Theme::query()->inRandomOrder()->create()->first()->id;
    }
}
