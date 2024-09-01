<?php

namespace Database\Seeders;

use Database\Seeders\Survey\SurveyCategorySeeder;
use Database\Seeders\Survey\SurveySeeder;
use Database\Seeders\Survey\SurveyStatusSeeder;
use Database\Seeders\Theme\ThemeSeeder;
use Database\Seeders\Theme\ThemeSettingSeeder;
use Database\Seeders\Theme\VariablePaletteSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void {
        $this->call(UserSeeder::class);
        $this->call(ThemeSeeder::class);
        $this->call(ThemeSettingSeeder::class);
        $this->call(VariablePaletteSeeder::class);
        $this->call(QuestionTypeSeeder::class);
        $this->call(SurveyCategorySeeder::class);
        $this->call(SurveyStatusSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(TagSeeder::class);
        $this->call(SurveySeeder::class);
    }
}