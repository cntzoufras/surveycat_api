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
//            \App\Models\Question::factory(2000)->create();
//            \App\Models\Survey\SurveyPage::factory(5)->create();
//            $this->call(UserSeeder::class);
        \App\Models\User::factory(50)->create();
        $this->call(QuestionTypeSeeder::class);
        $this->call(SurveyCategorySeeder::class);
        $this->call(SurveyStatusSeeder::class);
    }
}