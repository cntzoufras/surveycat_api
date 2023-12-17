<?php

namespace Database\Factories\Survey;

use App\Models\Survey\SurveyQuestion;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Survey\SurveyQuestion>
 */
class SurveyQuestionFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SurveyQuestion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $faker = Faker::create();
        return [
            'title'         => $faker->sentence(),
            'format_id'     => $faker->numberBetween(1, 10),
            'is_public'     => $faker->boolean(),
            'style_id'      => $faker->numberBetween(1, 5),
            'status'        => $faker->randomElement(['draft', 'published']),
            'question_tags' => json_encode($faker->words(8)),
            'views'         => $faker->numberBetween(0, 100000),
        ];
    }
}
