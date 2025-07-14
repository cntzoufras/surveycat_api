<?php

namespace Database\Seeders\Theme;

use App\Models\Theme\Theme;
use App\Models\User;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the path to the CSV file
        $csvPath = base_path('database/seeds/themes.csv');

        // Open the CSV file
        $file = fopen($csvPath, 'r');

        // Skip the header row
        fgetcsv($file);

        // Loop through each row in the CSV file
        while (($data = fgetcsv($file)) !== false) {
            $theme = [$data[0], $data[1]];
            $this->seedTheme($theme);
        }

        // Close the file
        fclose($file);
    }

    /**
     * Seed the question type to the database.
     *
     * @param array $theme
     */
    private function seedTheme(array $theme): void
    {
        $user_id = $this->getUserId();
        Theme::query()->create(['title' => $theme[0],
            'description' => $theme[1],
            'user_id' => $user_id,
        ]);
    }

    private function getUserId(): string
    {
        return User::query()->inRandomOrder()->first()->id;
    }


}
