<?php

namespace Database\Factories\Survey;

use App\Models\Theme\Theme;
use App\Models\Theme\ThemeSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Survey\Survey>
 */
class SurveyFactory extends Factory {

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $user = User::factory()->create();
        $theme = Theme::factory()->create(['name' => 'Monokai', 'description' => 'black white']);

        return [
            'title'              => fake()->title(),
            'description'        => fake()->sentence(),
            'survey_category_id' => fake()->numberBetween(1, 12),
            'survey_status_id'   => fake()->numberBetween(1, 3),
            'user_id'            => $user->id, 'theme_id' => $theme->id,
            'public_link'        => fake()->url(),
            'views'              => fake()->numberBetween(0, 1000000),
        ];
    }
}
