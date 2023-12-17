<?php

namespace Database\Factories\Theme;

use App\Models\Theme\Theme;
use App\Models\Theme\ThemeSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Theme\Theme>
 */
class ThemeFactory extends Factory {

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'id'          => fake()->uuid(),
            'title'       => fake()->sentence(3),
            'description' => fake()->paragraph(1),
            'footer'      => json_encode(["footer_title" => fake()->sentence(1)]),
        ];
    }
}
