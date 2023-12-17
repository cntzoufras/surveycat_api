<?php

namespace Database\Seeders\Survey;

use App\Models\Survey\Survey;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void {
        Survey::factory(3)->create();
    }
}
