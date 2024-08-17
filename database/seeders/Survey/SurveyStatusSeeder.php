<?php

namespace Database\Seeders\Survey;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveyStatusSeeder extends Seeder {

    /**
     * Run the database seeds.
     * https://help.surveymonkey.com/en/surveymonkey/manage/survey-summary/
     */
    public function run(): void {

        $data = [
            ['title'       => 'DRAFT',
             'description' => 'Click the DRAFT status to go directly to the Design section to continue editing your survey.',
            ],
            ['title'       => 'PUBLISHED',
             'description' => 'Click the PUBLISHED status to publish your survey and create a shareable public link.',
            ],
            ['title'       => 'OPEN',
             'description' => 'Your survey contains questions and has at least one Open or Draft collector.',
            ],
            ['title'       => 'CLOSED',
             'description' => 'All collectors are closed, and the survey is no longer collecting responses.',
            ],
        ];
        DB::table('survey_statuses')->insert($data);
    }
}
