<?php

namespace Database\Seeders\Theme;

use App\Models\Theme\ThemeSetting;
use App\Models\Theme\VariablePalette;
use Illuminate\Database\Seeder;

class VariablePaletteSeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void {

        // Get the path to the CSV file
        $csvPath = base_path('database/seeds/variable_palettes.csv');

        // Open the CSV file
        $file = fopen($csvPath, 'r');

        // Skip the header row
        fgetcsv($file);

        $theme_settings = ThemeSetting::all();
        if ($theme_settings->isEmpty()) {
            throw new \Exception("No ThemeSettings found. Ensure ThemeSettings have been seeded before running this seeder.");
        }

        // Loop through each row in the CSV file
        while (($data = fgetcsv($file)) !== false) {
            $variable_palette = [$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]];
            $this->seedVariablePalette($variable_palette);
        }

        // Close the file
        fclose($file);
    }

    /**
     * Seed the variable palette to the database.
     *
     * @param array $variable_palette
     */
    private function seedVariablePalette(array $variable_palette): void {
        $theme_setting = ThemeSetting::first();
        VariablePalette::query()->create([
            'title_color'          => $variable_palette[0],
            'question_color'       => $variable_palette[1],
            'answer_color'         => $variable_palette[2],
            'primary_accent'       => $variable_palette[3],
            'primary_background'   => $variable_palette[4],
            'secondary_accent'     => $variable_palette[5],
            'secondary_background' => $variable_palette[6],
            'theme_setting_id'     => $theme_setting->id,
        ]);
    }
}
