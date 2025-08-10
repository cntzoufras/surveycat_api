<?php

namespace Database\Seeders\Survey;

use App\Models\Respondent;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyPage;
use App\Models\Survey\SurveyQuestion;
use App\Models\Survey\SurveyQuestionChoice;
use App\Models\Survey\SurveyResponse;
use App\Models\Survey\SurveySettings;
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

        // Δημιουργία ψεύτικων απαντήσεων και υποβολών
        $this->createGlobalSurveyResponsesAndSubmissions(array_values($this->allSurveys));

    }

    private function processRow(array $data, ?Survey &$survey, ?SurveyPage &$page, string $userId, string $themeId, int &$pageSortIndex, int &$questionSortIndex): void
    {
        // Εάν νέα έρευνα?, δημιουργησε νέο μοντε΄λο και αρχικοποιησε το sort index στις σελίδες
        if (!$survey || $survey->title !== $data[0]) {
            $survey = Survey::create([
                'title' => $data[0],
                'description' => $data[1],
                'survey_category_id' => $data[2],
                'survey_status_id' => 1,
                'priority' => $data[4],
                'is_stock' => true,
                'user_id' => $userId,
                'theme_id' => $themeId,
                'created_at' => \Carbon\Carbon::now()->subDays(rand(0, 60)),
            ]);
            $this->allSurveys[$survey->id] = $survey;

            // Create default settings for the new survey
            $settings = SurveySettings::getDefaultSettings();
            $settings['survey_id'] = $survey->id;
            SurveySettings::create($settings);

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

    private function createGlobalSurveyResponsesAndSubmissions(array $surveys): void
    {
        if (empty($surveys)) {
            $this->command->info('No surveys found to create responses for.');
            return;
        }

        $faker = Faker::create();
        $totalResponses = 16213;
        $totalSubmissions = 9850;

        // Realistic UA pools to resemble desktop/mobile/tablet devices
        $desktopUAs = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 13_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15',
            'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Edg/126.0.0.0 Chrome/126.0.0.0 Safari/537.36',
        ];
        $mobileUAs = [
            'Mozilla/5.0 (iPhone; CPU iPhone OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Linux; Android 14; Pixel 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 13; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Mobile Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/126.0.0.0 Mobile/15E148 Safari/604.1',
        ];
        $tabletUAs = [
            'Mozilla/5.0 (iPad; CPU OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Linux; Android 13; SAMSUNG SM-T970) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
            'Mozilla/5.0 (iPad; CPU OS 17_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1',
        ];

        // Eager-load all questions and choices for all surveys to be more efficient
        $surveyIds = array_map(fn($s) => $s->id, $surveys);
        $allSurveyQuestions = SurveyQuestion::with('survey_question_choices')
            ->whereHas('survey_page', fn($q) => $q->whereIn('survey_id', $surveyIds))
            ->get()
            ->groupBy('survey_page.survey_id'); // Group questions by survey ID

        $this->command->info("Creating {$totalResponses} responses and {$totalSubmissions} submissions...");
        $bar = $this->command->getOutput()->createProgressBar($totalResponses);

        // Create a weighted list of survey IDs to make the distribution more realistic
        $categoryWeights = [
            'Beauty and Fashion' => 30,
            'Career and Work' => 25,
            'Business and Finance' => 20,
            'Arts and Culture' => 15,
            'Automotive' => 10,
        ];

        $categories = \App\Models\Survey\SurveyCategory::whereIn('title', array_keys($categoryWeights))->get()->keyBy('title');
        $surveysByCategory = collect($surveys)->groupBy('survey_category_id');

        $weightedSurveyIds = [];
        foreach ($categoryWeights as $title => $weight) {
            if (isset($categories[$title])) {
                $categoryId = $categories[$title]->id;
                if (isset($surveysByCategory[$categoryId])) {
                    $categorySurveyIds = $surveysByCategory[$categoryId]->pluck('id')->toArray();
                    for ($i = 0; $i < $weight; $i++) {
                        $weightedSurveyIds = array_merge($weightedSurveyIds, $categorySurveyIds);
                    }
                }
            }
        }

        for ($i = 0; $i < $totalResponses; $i++) {
            // Pick a random survey for this response based on the new weighted distribution
            $randomSurveyId = $faker->randomElement($weightedSurveyIds);
            $survey = collect($surveys)->firstWhere('id', $randomSurveyId);
            $surveyQuestions = $allSurveyQuestions->get($survey->id) ?? collect();

            // Determine the start date for the response first
            $startedAt = $faker->dateTimeBetween('-12 months', 'now');

            // Create a respondent with a historical creation date
            $respondent = Respondent::create([
                'email' => $faker->boolean(30) ? $faker->unique()->safeEmail : null,
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'age' => $faker->numberBetween(6, 80),
                'created_at' => $startedAt,
                'updated_at' => $startedAt,
            ]);

            // Πρόσθεσε string στο session
            $sessionId = $this->createSessionForRespondent($respondent, $faker);

            // Create the SurveyResponse record
            // Create a mix of completed, started but not finished, and not started surveys
            // Let's say 70% of responses lead to submissions
            $isCompleted = $faker->boolean(70); 
            $completedAt = null;
            if ($isCompleted) {
                $completionMinutes = $faker->numberBetween(1, 60); // Realistic completion time: 2 to 120 minutes
                $completedAt = (new \Carbon\Carbon($startedAt))->addMinutes($completionMinutes);
            }

            // Pick a device type with a realistic distribution, then assign a UA from that pool
            $deviceType = $faker->randomElement([
                'desktop','desktop','desktop','desktop','mobile','mobile','mobile','tablet' // ~50% desktop, ~37% mobile, ~13% tablet
            ]);
            $ua = $deviceType === 'desktop'
                ? $faker->randomElement($desktopUAs)
                : ($deviceType === 'mobile' ? $faker->randomElement($mobileUAs) : $faker->randomElement($tabletUAs));

            $surveyResponse = SurveyResponse::create([
                'ip_address' => $faker->ipv4,
                'device' => $ua, // store full user-agent string
                'started_at' => $startedAt,
                'completed_at' => $completedAt,
                'session_id' => $sessionId,
                'survey_id' => $survey->id,
                'respondent_id' => $respondent->id,
                'country' => $this->pickWeightedCountry($faker),
            ]);

            // If it's a completed survey, create the submission data
            if ($isCompleted) {
                $submissionData = [];
                if ($surveyQuestions->isNotEmpty()) {
                    foreach ($surveyQuestions as $q) {
                        switch ($q->question_type_id) {
                            case 1: // Multiple Choice
                            case 7: // Dropdown
                                if ($q->survey_question_choices->isNotEmpty()) {
                                    $choice = $q->survey_question_choices->random();
                                    $submissionData[$q->id] = $choice->id;
                                }
                                break;

                            case 2: // Checkboxes
                                if ($q->survey_question_choices->isNotEmpty()) {
                                    $ids = $q->survey_question_choices->pluck('id')->toArray();
                                    $pick = $faker->randomElements($ids, rand(1, count($ids)));
                                    $submissionData[$q->id] = $pick;
                                }
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
                }

                SurveySubmission::create([
                    'survey_id' => $survey->id,
                    'survey_response_id' => $surveyResponse->id,
                    'submission_data' => json_encode($submissionData),
                    'created_at' => $completedAt,
                    'updated_at' => $completedAt,
                ]);
            }
            $bar->advance();
        }
        $bar->finish();
        $this->command->info("\nSeeding of responses and submissions complete.");

        // Add one explicit desktop SurveyResponse resembling the provided production sample
        // We will link it to a random existing survey and a fresh respondent/session
        if (!empty($surveys)) {
            $sampleSurvey = $surveys[array_rand($surveys)];
            $sampleRespondent = Respondent::create([
                'email' => null,
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'age' => $faker->numberBetween(18, 70),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $sampleSessionId = $this->createSessionForRespondent($sampleRespondent, $faker);

            $sampleStarted = \Carbon\Carbon::parse('2025-08-10 17:23:22');
            $sampleCompleted = \Carbon\Carbon::parse('2025-08-10 17:23:35');

            SurveyResponse::create([
                'ip_address' => '2.84.96.58',
                'device' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
                'started_at' => $sampleStarted,
                'completed_at' => $sampleCompleted,
                'session_id' => $sampleSessionId,
                'survey_id' => $sampleSurvey->id,
                'respondent_id' => $sampleRespondent->id,
                'country' => 'Greece',
            ]);
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

    /**
     * Pick a realistic country name using weighted continents and a boost for Greece.
     * Intended only for seed/demo data; production uses GeoIP.
     */
    private function pickWeightedCountry($faker): string
    {
        // 1) Weighted continent selection (prioritize Europe, Asia, Americas)
        $continents = [
            'Europe' => 45,
            'Asia' => 30,
            'Americas' => 20,
            'Africa' => 3,
            'Oceania' => 2,
        ];

        $continentPool = [];
        foreach ($continents as $c => $w) {
            for ($i = 0; $i < $w; $i++) {
                $continentPool[] = $c;
            }
        }
        $continent = $faker->randomElement($continentPool);

        // 2) Curated countries per continent (exclude microstates and rare territories)
        $countriesByContinent = [
            'Europe' => [
                'Germany','France','United Kingdom','Italy','Spain','Netherlands','Sweden','Poland','Greece','Portugal','Ireland','Austria','Belgium','Romania','Czechia','Hungary','Denmark','Finland','Norway','Switzerland'
            ],
            'Asia' => [
                'India','Indonesia','Philippines','Singapore','Malaysia','Japan','South Korea','Thailand','Vietnam','United Arab Emirates','Saudi Arabia','Pakistan','Bangladesh','Taiwan','Israel'
            ],
            'Americas' => [
                'United States','Canada','Mexico','Brazil','Argentina','Chile','Colombia','Peru','Ecuador'
            ],
            'Africa' => [
                'South Africa','Egypt','Nigeria','Morocco','Kenya','Ghana','Ethiopia','Algeria','Tunisia'
            ],
            'Oceania' => [
                'Australia','New Zealand'
            ],
        ];

        $list = $countriesByContinent[$continent] ?? ['United States'];

        // 3) Optional per-country weights (light bias to larger markets)
        $weights = [
            'United States' => 6,
            'Germany' => 4,
            'United Kingdom' => 4,
            'France' => 3,
            'Italy' => 3,
            'Spain' => 3,
            'India' => 5,
            'Japan' => 3,
            'Brazil' => 3,
            'Canada' => 2,
            'Mexico' => 2,
            'Indonesia' => 3,
            'Philippines' => 2,
            'South Korea' => 2,
        ];

        // Boost Greece visibly when Europe is selected
        if ($continent === 'Europe') {
            $weights['Greece'] = ($weights['Greece'] ?? 0) + 6; // strong boost
        }

        // Build weighted pool
        $pool = [];
        foreach ($list as $country) {
            $w = $weights[$country] ?? 1;
            for ($i = 0; $i < $w; $i++) {
                $pool[] = $country;
            }
        }

        // Global small chance to pick Greece regardless of continent (extra promotion)
        if ($faker->numberBetween(1, 100) <= 5) { // 5% global boost
            return 'Greece';
        }

        return $faker->randomElement($pool);
    }
}
