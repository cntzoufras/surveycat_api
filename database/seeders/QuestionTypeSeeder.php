<?php
    
    namespace Database\Seeders;
    
    use App\Models\QuestionType;
    use App\Models\SurveyQuestionType;
    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Storage;
    
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
                $question_type = $data[0]; // Assuming the question type is in the first column
                
                // Process and seed the question type to the database
                $this->seedQuestionType($question_type);
            }
            
            // Close the file
            fclose($file);
        }
        
        /**
         * Seed the question type to the database.
         *
         * @param string $question_type
         */
        private function seedQuestionType(string $question_type): void {
            QuestionType::create(['title' => $question_type]);
        }
    }
