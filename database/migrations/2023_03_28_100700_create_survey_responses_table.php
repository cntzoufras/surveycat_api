<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ip_address');
            $table->string('device');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('submitted_at')->default(null);
            $table->string('session_id')->index();
            $table->foreignUuid('survey_id')->constrained('surveys');
            $table->foreignUuid('survey_submission_id')->constrained('survey_submissions');
            $table->foreignUuid('respondent_id')->constrained('respondents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropForeign(['survey_id', 'survey_submission_id', 'respondent_id']);
        });
        Schema::dropIfExists('survey_responses');
    }
};
