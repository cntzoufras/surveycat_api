<?php
    
    namespace Database\Factories;
    
    use App\Models\Question;
    use Faker\Factory as Faker;
    use Illuminate\Database\Eloquent\Factories\Factory;
    use Illuminate\Support\Arr;
    
    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SurveyPages>
     */
    class SurveyPagesFactory extends Factory {
        
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition(): array {
            $faker = Faker::create();
            return [
                'pages' => Arr::random(['Page A', 'Page B', 'Page C', 'Page D', 'Page E', 'Page F']),
                'uv'    => $faker->numberBetween('500', '20000'),
                'pv'    => $faker->numberBetween('500', '20000'),
                'amt'   => $faker->numberBetween('500', '20000'),
            ];
        }
    }
