<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('survey_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->jsonb('submission_data');
            $table->foreignUuid('survey_id')->references('id')->on('surveys');
            $table->foreignUuid('survey_response_id')->nullable()->constrained('survey_responses');
            $table->timestamps();
            $table->index(['survey_id', 'survey_response_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_submissions', function (Blueprint $table) {
            $table->dropIndex('survey_submissions_survey_id_survey_response_id_index');

            $table->dropForeign('survey_submissions_survey_id_foreign');
            $table->dropForeign('survey_submissions_survey_response_id_foreign');
        });

        Schema::dropIfExists('survey_submissions');

    }
};
