<?php

namespace Database\Seeders\Survey;

use App\Models\Survey\Survey;
use App\Models\Survey\SurveyPage;
use App\Models\Survey\SurveyQuestion;
use App\Models\Survey\SurveyResponse;
use App\Models\Survey\SurveySubmission;
use App\Models\Respondent;
use App\Models\Survey\SurveyQuestionChoice;
use App\Models\User;
use App\Models\Theme\Theme;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SurveySeeder extends Seeder {

    public function run(): void {
        // Get the superadmin user (first user from UserSeeder)
        $superadmin = User::query()->first();

        // Get the first theme (from ThemeSeeder)
        $theme = Theme::query()->first();

        // Get the path to the CSV file
        $csvPath = base_path('database/seeds/stock_surveys.csv');

        // Open the CSV file
        $file = fopen($csvPath, 'r');

        // Skip the header row
        fgetcsv($file);

        $currentSurvey = null;
        $currentPage = null;

        // Loop through each row in the CSV file
        while (($data = fgetcsv($file)) !== false) {
            $this->processRow($data, $currentSurvey, $currentPage, $superadmin->id, $theme->id);
        }

        // Close the file
        fclose($file);

        // Create responses for the generated survey
        if ($currentSurvey) {
            $this->createSurveyResponses($currentSurvey);
        }
    }

    /**
     * Process each row in the CSV file.
     *
     * @param array $data
     * @param Survey|null $currentSurvey
     * @param SurveyPage|null $currentPage
     * @param string $userId
     * @param string $themeId
     */
    private function processRow(array $data, &$currentSurvey, &$currentPage, string $userId, string $themeId): void {
        // Check if we need to create a new survey
        if (!$currentSurvey || $currentSurvey->title !== $data[0]) {
            $currentSurvey = $this->createSurvey($data, $userId, $themeId);
        }

        // Check if we need to create a new page
        if (!$currentPage || $currentPage->title !== $data[5]) {
            $currentPage = $this->createSurveyPage($data, $currentSurvey);
        }

        // Create the question
        $this->createSurveyQuestion($data, $currentPage);
    }

    /**
     * Create a new survey.
     *
     * @param array $data
     * @param string $userId
     * @param string $themeId
     *
     * @return Survey
     */
    private function createSurvey(array $data, string $userId, string $themeId): Survey {
        return Survey::query()->create([
            'title'              => $data[0],
            'description'        => $data[1],
            'survey_category_id' => $data[2],  // Use survey_category_id from the CSV
            'survey_status_id'   => $data[3],  // Use status_id from the CSV
            'priority'           => $data[4],  // Adding priority from the CSV
            'is_stock'           => true,      // This is a stock survey
            'user_id'            => $userId,   // Associating with the superadmin user
            'theme_id'           => $themeId,  // Associating with the first theme
        ]);
    }

    /**
     * Create a new survey page.
     *
     * @param array $data
     * @param Survey $survey
     *
     * @return SurveyPage
     */
    private function createSurveyPage(array $data, Survey $survey): SurveyPage {
        return SurveyPage::query()->create([
            'title'             => $data[5],
            'description'       => $data[6],
            'survey_id'         => $survey->id,
            'require_questions' => filter_var($data[7], FILTER_VALIDATE_BOOLEAN), // Handling require_questions
        ]);
    }

    /**
     * Create a new survey question and assign random tags.
     *
     * @param array $data
     * @param SurveyPage $page
     */
    private function createSurveyQuestion(array $data, SurveyPage $page): void {
        $additionalSettings = json_encode([
            'color' => $data[11],
            'align' => $data[12],
            'font'  => $data[13],
        ]);

        // Create the survey question
        $surveyQuestion = SurveyQuestion::query()->create([
            'title'               => $data[8],
            'is_required'         => filter_var($data[9], FILTER_VALIDATE_BOOLEAN),
            'question_type_id'    => $data[10],
            'survey_page_id'      => $page->id,
            'additional_settings' => $additionalSettings,
        ]);

        // Assign random tags to the survey question
        $tags = Tag::inRandomOrder()->take(rand(1, 5))->get();

        // Attach each tag to the survey question using the morphToMany relationship
        foreach ($tags as $tag) {
            $surveyQuestion->tags()->attach($tag);
        }
    }

    /**
     * Create survey responses and submissions for the given survey.
     *
     * @param Survey $survey
     */
    private function createSurveyResponses(Survey $survey): void {
        $faker = Faker::create();

        // Get all survey questions for the given survey
        $surveyQuestions = SurveyQuestion::whereHas('survey_page', function ($query) use ($survey) {
            $query->where('survey_id', $survey->id);
        })->get();

        for ($i = 0; $i < 100; $i++) {
            // Create a respondent
            $respondent = Respondent::create([
                'email'   => $faker->unique()->safeEmail,
                'details' => json_encode([
                    'age'      => $faker->numberBetween(18, 65),
                    'gender'   => $faker->randomElement(['male', 'female']),
                    'location' => $faker->city,
                ]),
            ]);

            // Create a survey response
            $surveyResponse = SurveyResponse::create([
                'ip_address'    => $faker->ipv4,
                'device'        => $faker->randomElement(['desktop', 'mobile', 'tablet']),
                'started_at'    => $faker->dateTimeBetween('-1 week', '-1 day'),
                'completed_at'  => $faker->boolean(80) ? $faker->dateTimeBetween('-1 day', 'now') : null,
                'session_id'    => $faker->uuid,
                'survey_id'     => $survey->id,
                'respondent_id' => $respondent->id,
                'country'       => $faker->country,
                'timezone'      => $faker->timezone,
            ]);

            // Prepare submission data
            $submissionData = [];

            foreach ($surveyQuestions as $question) {
                if ($question->question_type_id == 1) { // Assuming 1 is for multiple-choice
                    $choice = SurveyQuestionChoice::where('survey_question_id', $question->id)->inRandomOrder()->first();
                    $submissionData[$question->id] = $choice ? $choice->id : null;
                } else {
                    $submissionData[$question->id] = $faker->sentence;
                }
            }

            // Create a survey submission
            SurveySubmission::create([
                'submission_data'    => json_encode($submissionData),
                'survey_id'          => $survey->id,
                'survey_response_id' => $surveyResponse->id,
            ]);
        }
    }
}
