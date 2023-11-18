<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('survey_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->jsonb('submission_data')->default(null);
            $table->foreignUuid('survey_id')->references('id')->on('surveys');
            $table->foreignUuid('survey_response_id')->references('id')->on('survey_responses');
            $table->foreignUuid('respondent_id')->references('id')->on('respondents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('survey_submissions', function (Blueprint $table) {
            $table->dropForeign(['respondent_id']);
            $table->dropForeign(['survey_response_id']);
        });
        Schema::dropIfExists('survey_submissions');
    }
};
