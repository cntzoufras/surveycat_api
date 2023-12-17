<?php

namespace Database\Factories\Theme;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Theme\ThemeVariable>
 */
class ThemeVariableFactory extends Factory {

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'id'                       => fake()->uuid(),
            'primary_background_alpha' => rand(0.01, 99.99),
            'theme_thumb'              => fake()->url(),
        ];
    }
}
