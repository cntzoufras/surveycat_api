<?php
    
    namespace Database\Seeders;
    
    use App\Models\SurveyCategory;
    use App\Models\User;
    use Illuminate\Database\Seeder;
    use UserSeeder;
    
    class DatabaseSeeder extends Seeder {
        
        /**
         * Seed the application's database.
         *
         * @return void
         */
        public function run() {
//            \App\Models\Question::factory(2000)->create();
//            \App\Models\SurveyPage::factory(5)->create();
//            $this->call(UserSeeder::class);
            \App\Models\User::factory(50)->create();
            $this->call(QuestionTypeSeeder::class);
            $this->call(SurveyCategorySeeder::class);
            $this->call(SurveyStatusSeeder::class);
        }
    }