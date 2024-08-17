<?php

namespace Database\Seeders\Survey;

use App\Models\Survey\Survey;
use Illuminate\Database\Seeder;
use App\Models\Survey\SurveyPage;
use App\Models\Survey\SurveyQuestion;
use App\Models\User;
use App\Models\Theme\Theme;
use App\Models\Tag;

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
}

