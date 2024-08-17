<?php

namespace Database\Factories\Theme;

use Illuminate\Database\Eloquent\Factories\Factory;

class VariablePaletteFactory extends Factory {

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'id'                   => fake()->numberBetween(1, 1),
            'answer_color'         => fake()->hexColor(),
            'primary_accent'       => fake()->randomElement(['', 'bold', 'italic', 'underline']),
            'primary_background'   => fake()->url(),
            'question_color'       => fake()->hexColor(),
            'secondary_accent'     => fake()->randomElement(['', 'bold', 'italic', 'underline']),
            'secondary_background' => fake()->hexColor(),
            'title_color'          => fake()->hexColor(),
        ];
    }
}
