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
                $table->timestamps();
                $table->foreignUuId('survey_id')->references('id')->on('surveys')->onDelete('cascade');
                $table->foreignUuId('survey_response_id')->references('id')->on('survey_responses');
                $table->foreignUuid('respondent_id')->references('id')->on('respondents');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('survey_submissions', function (Blueprint $table) {
                $table->dropForeign('survey_submissions_respondent_id_foreign');
                $table->dropForeign('survey_submissions_survey_response_id_foreign');
            });
            Schema::dropIfExists('survey_submissions');
        }
    };
