<?php

namespace Database\Seeders\Theme;

use App\Models\Theme\VariablePalette;
use Illuminate\Database\Seeder;

class VariablePaletteSeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Get the path to the CSV file
        $csvPath = base_path('database/seeds/variable_palettes.csv');

        // Open the CSV file
        $file = fopen($csvPath, 'r');

        // Skip the header row
        fgetcsv($file);

        // Loop through each row in the CSV file
        while (($data = fgetcsv($file)) !== false) {
            $variable_palette = [$data[0], $data[1], $data[2]];
            $this->seedVariablePalette($variable_palette);
        }

        // Close the file
        fclose($file);
    }

    /**
     * Seed the variable palette to the database.
     *
     * @param array $variable_palette
     */
    private function seedVariablePalette(array $variable_palette): void {
        VariablePalette::query()->create(['title'       => $variable_palette[0],
                                          'description' => $variable_palette[1],
                                          'footer'      => $variable_palette[2],
        ]);
    }
}
