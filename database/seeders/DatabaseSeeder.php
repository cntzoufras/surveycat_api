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
            \App\Models\Question::factory(2000)->create();
            \App\Models\SurveyPage::factory(2000)->create();
            \App\Models\User::factory(50)->create();
        }
    }