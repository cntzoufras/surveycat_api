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

        fgetcsv($file); // Skip header row
        while (($data = fgetcsv($file)) !== false) {
            // If theme_id is not provided, cycle through available themes
            $theme_id = $data[0] ?: $themes[$theme_index++ % $theme_count];

            // Construct the full URL for the thumb
            $appUrl = env('APP_URL', 'http://surveycat.test'); // Fallback to localhost if APP_URL is not set
            $thumbUrl = $appUrl . $data[7];

            // Create the theme setting using the CSV data
            ThemeSetting::query()->create([
                'theme_id' => $theme_id,
                'settings' => [
                    'typography'               => [
                        'fontFamily'   => $data[1],
                        'fontSize'     => $data[2],
                        'headingStyle' => [
                            'H1' => $data[3],
                            'H2' => $data[4],
                        ],
                    ],
                    'primary_background_alpha' => $data[5],
                    'layout'                   => $data[6],
                    'thumb'                    => $thumbUrl,
                ],
            ]);
        }
        fclose($file);
    }
}
