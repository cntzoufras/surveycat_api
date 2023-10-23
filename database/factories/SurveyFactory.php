<?php
    
    namespace Database\Factories;
    
    use Illuminate\Database\Eloquent\Factories\Factory;
    
    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Survey>
     */
    class SurveyFactory extends Factory {
        
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition(): array {
            return [
                'title'                 => fake()->sentence(),
                'page_title'            => fake()->sentence(),
                'show_page_title'       => fake()->numberBetween(0, 1),
                'show_page_numbers'     => fake()->numberBetween(0, 1),
                'show_question_numbers' => fake()->numberBetween(0, 1),
                'show_progress_bar'     => fake()->numberBetween(0, 1),
                'required_asterisks'    => fake()->numberBetween(0, 1),
                'public_link'           => fake()->url(),
                'banner_image'          => fake()->imageUrl(),
                'views'                 => fake()->numberBetween(0, 1000000),
                'survey_category_id'    => fake()->numberBetween(1, 12),
                'survey_status_id'      => fake()->numberBetween(1, 5),
                'theme_setting_id'      => fake()->numberBetween(1, 5),
            ];
        }
    }
