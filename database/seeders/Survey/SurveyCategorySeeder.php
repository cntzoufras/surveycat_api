<?php

namespace Database\Seeders\Survey;

use App\Models\Survey\SurveyCategory;
use Illuminate\Database\Seeder;

class SurveyCategorySeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void {
        SurveyCategory::factory()->count(68)->create();
    }
}
