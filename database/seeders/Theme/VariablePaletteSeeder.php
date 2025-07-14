<?php

namespace Database\Seeders\Theme;

use App\Models\Theme\Theme;
use App\Models\Theme\ThemeSetting;
use App\Models\Theme\VariablePalette;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class VariablePaletteSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VariablePalette::query()->truncate();

        // Get the path to the CSV file
        $csvPath = base_path('database/seeds/variable_palettes.csv');
        if (!file_exists($csvPath)) {
            $this->command->error("Variable palettes CSV file not found at: {$csvPath}");
            return;
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file);


        // Loop through each row in the CSV file
        while (($data = fgetcsv($file)) !== false) {
            $data = array_combine($header, $data);

            // Find the theme by its title from the CSV
            $theme = Theme::query()->where('title', trim($data['theme_title']))->first();

            if (!$theme || !$theme->theme_setting) {
                Log::warning("Skipping palette for '{$data['theme_title']}' because theme_setting_id '{$data['theme_setting_id']}' was not found.");
                continue;
            }

            // Create the VariablePalette record using the ID from the CSV
            VariablePalette::query()->create([
                'name' => trim($data['name']),
                'is_active' => filter_var(trim($data['is_active']), FILTER_VALIDATE_BOOLEAN),
                'title_color' => trim($data['title_color']),
                'question_color' => trim($data['question_color']),
                'answer_color' => trim($data['answer_color']),
                'primary_accent' => trim($data['primary_accent']),
                'primary_background' => trim($data['primary_background']),
                'secondary_accent' => trim($data['secondary_accent']),
                'secondary_background' => trim($data['secondary_background']),
                'theme_setting_id' => $theme->theme_setting->id,
            ]);
        }

        // Close the file
        fclose($file);
    }
}
