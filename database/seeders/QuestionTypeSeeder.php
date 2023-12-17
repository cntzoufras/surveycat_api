<?php

namespace Database\Seeders;

use App\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Get the path to the CSV file
        $csvPath = base_path('database/seeds/question_types.csv');

        // Open the CSV file
        $file = fopen($csvPath, 'r');

        // Skip the header row
        fgetcsv($file);

        // Loop through each row in the CSV file
        while (($data = fgetcsv($file)) !== false) {
            $question_type = [$data[0], $data[1]];
            $this->seedQuestionType($question_type);
        }

        // Close the file
        fclose($file);
    }

    /**
     * Seed the question type to the database.
     *
     * @param array $question_type
     */
    private function seedQuestionType(array $question_type): void {
        QuestionType::query()->create(['title' => $question_type[0], 'description' => $question_type[1]]);
    }
}
