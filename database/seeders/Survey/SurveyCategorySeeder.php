<?php

namespace Database\Seeders\Survey;

use App\Models\Survey\SurveyCategory;
use Illuminate\Database\Seeder;

class SurveyCategorySeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Get the path to the CSV file
        $csvPath = base_path('database/seeds/survey_categories.csv');

        // Open the CSV file
        $file = fopen($csvPath, 'r');

        // Skip the header row
        fgetcsv($file);

        // Loop through each row in the CSV file
        while (($data = fgetcsv($file)) !== false) {
            $survey_category = [$data[0], $data[1]];
            $this->seedSurveyCategory($survey_category);
        }

        // Close the file
        fclose($file);
    }

    /**
     * Seed the question type to the database.
     *
     * @param array $survey_category
     */
    private function seedSurveyCategory(array $survey_category): void {
        SurveyCategory::query()->create([
            'title'       => $survey_category[0],
            'description' => $survey_category[1],
        ]);
    }
}
