<?php
    
    namespace Database\Seeders;
    
    use App\Models\SurveyQuestionType;
    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Storage;
    
    class SurveyQuestionTypeSeeder extends Seeder {
        
        /**
         * Run the database seeds.
         */
        public function run(): void {
            // Get the path to the CSV file
            $csvPath = storage_path('app/public/path/to/your/csv/file.csv');
            
            // Open the CSV file
            $file = fopen($csvPath, 'r');
            
            // Skip the header row
            fgetcsv($file);
            
            // Loop through each row in the CSV file
            while (($data = fgetcsv($file)) !== false) {
                $questionType = $data[0]; // Assuming the question type is in the first column
                
                // Process and seed the question type to the database
                $this->seedQuestionType($questionType);
            }
            
            // Close the file
            fclose($file);
        }
        
        /**
         * Seed the question type to the database.
         *
         * @param string $questionType
         */
        private function seedQuestionType(string $questionType): void {
            SurveyQuestionType::create(['name' => $questionType]);
        }
    }
