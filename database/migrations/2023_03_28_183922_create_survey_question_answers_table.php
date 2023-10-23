<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Str;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_question_answers', function (Blueprint $table) {
                $table->uuid('id')->primary()->index();
                $table->jsonb('answer');
                $table->timestamps();
//                $table->uuid('survey_question_id');
//                $table->uuid('survey_submission_id');
//                $table->uuid('survey_respondent_id');
                
                $table->foreignUuId('survey_question_id')->references('id')->on('survey_questions');
                $table->foreignUuid('survey_submission_id')->references('id')->on('survey_submissions');
                $table->foreignUuid('survey_respondent_id')->references('id')->on('survey_respondents');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_question_answers');
        }
    };
