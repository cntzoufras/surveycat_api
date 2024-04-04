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
        $csvPath = base_path('database/seeds/theme_settings.csv');
        if (!file_exists($csvPath) || !is_readable($csvPath)) {
            throw new \RuntimeException("Unable to find or read the file at: {$csvPath}");
        }

        $themes = Theme::query()->pluck('id')->all(); // Fetch all theme IDs
        $theme_index = 0;
        $theme_count = count($themes);

        $file = fopen($csvPath, 'r');

        fgetcsv($file);
        while (($data = fgetcsv($file)) !== false) {
            if (!empty($data[0])) {
                $theme_id = $themes[$theme_index % $theme_count];
                $this->seedThemeSetting($data[0], $theme_id);
                $theme_index++;
            }
        }
        fclose($file);
    }

    private function seedThemeSetting(string $theme_setting, $theme_id): void {

        $decoded = json_decode($theme_setting, true);

        if (!is_array($decoded)) {
            \Log::warning("JSON decoding failed or didn't produce an array", ['json' => $theme_setting]);
            return;
        }

        ThemeSetting::query()->create([
            'settings' => $decoded,
            'theme_id' => $theme_id,
        ]);
    }

}
