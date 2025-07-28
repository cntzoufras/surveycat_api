<?php

namespace Database\Seeders\Survey;

use App\Models\Respondent;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyPage;
use App\Models\Survey\SurveyQuestion;
use App\Models\Survey\SurveyQuestionChoice;
use App\Models\Survey\SurveyResponse;
use App\Models\Survey\SurveySubmission;
use App\Models\Tag;
use App\Models\Theme\Theme;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SurveySeeder extends Seeder
{
    private array $allSurveys = [];

    public function run(): void
    {
        $superadmin = User::first();
        $theme = Theme::first();

        // Άνοιγμα CSV
        $csvPath = base_path('database/seeds/stock_surveys.csv');
        if (!file_exists($csvPath)) {
            $this->command->error("Missing {$csvPath}");
            return;
        }
        $file = fopen($csvPath, 'r');
        fgetcsv($file); // παράλειψη κεφαλίδας

        $currentSurvey = null;
        $currentPage = null;
        $questionSortIndex = 0;
        $pageSortIndex = 0;

        // Επεξεργασία γραμμών
        while ($data = fgetcsv($file)) {
            $this->processRow($data, $currentSurvey, $currentPage, $superadmin->id, $theme->id, $pageSortIndex, $questionSortIndex);
        }
        fclose($file);

        // Δημιουργία ψεύτικων απαντήσεων για κάθε έρευνα
        foreach ($this->allSurveys as $survey) {
            $this->createSurveyResponses($survey);
        }

    }

    private function processRow(array $data, ?Survey &$survey, ?SurveyPage &$page, string $userId, string $themeId, int &$pageSortIndex, int &$questionSortIndex): void
    {
        // Εάν νέα έρευνα?, δημιουργησε νέο μοντε΄λο και αρχικοποιησε το sort index στις σελίδες
        if (!$survey || $survey->title !== $data[0]) {
            $survey = Survey::create([
                'title' => $data[0],
                'description' => $data[1],
                'survey_category_id' => $data[2],
                'survey_status_id' => $data[3],
                'priority' => $data[4],
                'is_stock' => true,
                'user_id' => $userId,
                'theme_id' => $themeId,
            ]);
            $this->allSurveys[$survey->id] = $survey;
            $pageSortIndex = 0;
        }

        // Εάν νέα σελίδα, τότε δημιούργησε νέο μοντε΄λο και αύξησε το sort_index κατά 1
        if (!$page || $page->title !== $data[5]) {
            $page = SurveyPage::create([
                'title' => $data[5],
                'description' => $data[6],
                'survey_id' => $survey->id,
                'sort_index' => $pageSortIndex,
                'require_questions' => filter_var($data[7], FILTER_VALIDATE_BOOLEAN),
            ]);
            $pageSortIndex++;
            $questionSortIndex = 0; // Το sort_index σε νέα ερώτηση της σελίδας ξεκινά από 0
        }

        // Καταχώριση ερώτησης και επιλογών
        $this->createSurveyQuestionWithChoices($data, $page, $questionSortIndex);
    }

    private function createSurveyQuestionWithChoices(array $data, SurveyPage $page, int &$questionSortIndex): void
    {
        $questionTypeId = (int)$data[10];
        $additional = json_encode([
            'color' => $data[11],
            'align' => $data[12],
            'font' => $data[13],
        ]);

        // Δημιουργία ερώτησης
        $surveyQuestion = SurveyQuestion::create([
            'title' => $data[8],
            'is_required' => filter_var($data[9], FILTER_VALIDATE_BOOLEAN),
            'question_type_id' => $questionTypeId,
            'survey_page_id' => $page->id,
            'sort_index' => $questionSortIndex,
            'additional_settings' => $additional,
        ]);

        $questionSortIndex++;

        if (in_array($questionTypeId, [1, 2, 7], true)) {
            if (isset($data[14]) && !empty($data[14])) {
                $labels = explode('|', $data[14]);
                foreach ($labels as $idx => $content) {
                    SurveyQuestionChoice::create([
                        'survey_question_id' => $surveyQuestion->id,
                        'content' => trim($content), // Use trim() to clean up any whitespace
                        'sort_index' => $idx,
                    ]);
                }
            }
        }

        // Ειδική περ΄ίπτβωση για Best-Worst slider (Type 5), που έχει πιο fixed labels
        if ($questionTypeId === 5) {
            $labels = ['Lowest', 'Low', 'Medium', 'High', 'Highest'];
            foreach ($labels as $idx => $content) {
                SurveyQuestionChoice::create([
                    'survey_question_id' => $surveyQuestion->id,
                    'content' => $content,
                    'sort_index' => $idx,
                ]);
            }
        }

        // Δημιουργία τυχαίων tags
        Tag::inRandomOrder()->take(rand(1, 3))
            ->each(fn($tag) => $surveyQuestion->tags()->attach($tag));
    }

    // Δημιουργία ψεύτικων απαντήσεων
    private function createSurveyResponses(Survey $survey): void
    {
        $faker = Faker::create();

        // Eager-load τις επιλογές
        $surveyQuestions = SurveyQuestion::with('survey_question_choices')
            ->whereHas('survey_page', fn($q) => $q->where('survey_id', $survey->id))
            ->get();

        for ($i = 0; $i < 100; $i++) {
            // Δημιούργησε νέους ανταποκριτές
            $respondent = Respondent::create([
                'email' => $faker->boolean(30) ? $faker->unique()->safeEmail : null,
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'age' => $faker->numberBetween(6, 80),
            ]);

            // Πρόσθεσε string στο session
            $sessionId = $this->createSessionForRespondent($respondent, $faker);

            // Ενημέρωση ολοκληρωμένης έρευνας
            $startedAt = $faker->dateTimeBetween('-1 month', '-1 day');
            $completedAt = $faker->boolean(80)
                ? $faker->dateTimeBetween('-1 day', 'now')
                : null;

            $surveyResponse = SurveyResponse::create([
                'ip_address' => $faker->ipv4,
                'device' => $faker->randomElement(['desktop', 'mobile', 'tablet']),
                'started_at' => $startedAt,
                'completed_at' => $completedAt,
                'session_id' => $sessionId,
                'survey_id' => $survey->id,
                'respondent_id' => $respondent->id,
                'country' => $faker->country,
            ]);

            if ($completedAt) {
                $submissionData = [];

                foreach ($surveyQuestions as $q) {
                    switch ($q->question_type_id) {
                        case 1: // Multiple Choice
                        case 7: // Dropdown
                            $choice = $q->survey_question_choices->random();
                            $submissionData[$q->id] = $choice->id;
                            break;

                        case 2: // Checkboxes
                            $ids = $q->survey_question_choices->pluck('id')->toArray();
                            $pick = $faker->randomElements($ids, rand(1, count($ids)));
                            $submissionData[$q->id] = $pick;
                            break;

                        case 3: // Single Textbox
                            $submissionData[$q->id] = $faker->sentence();
                            break;

                        case 4: // Star Rating 1–5
                            $submissionData[$q->id] = $faker->numberBetween(1, 5);
                            break;

                        case 5: // Best-Worst slider (1–100)
                            $submissionData[$q->id] = $faker->numberBetween(1, 100);
                            break;

                        case 6: // Comment Box
                            $submissionData[$q->id] = $faker->paragraph();
                            break;

                        default:
                            $submissionData[$q->id] = null;
                    }
                }

                SurveySubmission::create([
                    'survey_id' => $survey->id,
                    'survey_response_id' => $surveyResponse->id,
                    'submission_data' => json_encode($submissionData),
                ]);
            }
        }
    }

    private function createSessionForRespondent(Respondent $respondent, $faker): string
    {
        $sessionId = Str::random(40);
        DB::table('sessions')->insert([
            'id' => $sessionId,
            'user_id' => null,
            'ip_address' => $faker->ipv4,
            'user_agent' => $faker->userAgent,
            'payload' => base64_encode('{}'),
            'last_activity' => now()->timestamp,
        ]);
        return $sessionId;
    }
}
