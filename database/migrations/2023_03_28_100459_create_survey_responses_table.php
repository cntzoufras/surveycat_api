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
            $table->timestamp('completed_at')->nullable();
            $table->string('session_id');
            $table->foreignUuid('survey_id')->constrained('surveys');
            $table->foreignUuid('respondent_id')->nullable()->constrained('respondents');
            $table->string('country');
            $table->string('timezone');
            $table->timestamps();
            $table->index(['session_id', 'survey_id', 'respondent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropForeign(['respondent_id']);
            $table->dropForeign(['survey_id']);
        });
        Schema::dropIfExists('survey_responses');
    }
};
