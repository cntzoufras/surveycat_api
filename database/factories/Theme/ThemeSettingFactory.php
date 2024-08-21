<?php

namespace Database\Factories\Theme;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Theme\ThemeSetting>
 */
class ThemeSettingFactory extends Factory {

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'id'       => fake()->numberBetween(1, 3),
//            'title'    => fake()->title,
//            'footer'   => json_encode(fake()->words(4)),
            'settings' => json_encode(fake()->words(8)),
        ];
    }
}
