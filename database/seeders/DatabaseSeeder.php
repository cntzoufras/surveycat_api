<?php
    
    namespace Database\Seeders;
    
    use Illuminate\Database\Seeder;
    
    class DatabaseSeeder extends Seeder {
        
        /**
         * Seed the application's database.
         *
         * @return void
         */
        public function run() {
            \App\Models\Question::factory(100)->create();
            \App\Models\SurveyPages::factory(100)->create();
        }
    }
