<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_question_answers', function (Blueprint $table) {
                $table->uuid('id')->primary()->unique()->index();
                $table->jsonb('answers');
                $table->jsonb('metadata');
                $table->timestamps();
                $table->foreignUuId('survey_question_id')->references('id')->on('survey_questions');
                $table->foreignUuid('survey_submission_id')->references('id')->on('survey_submissions');
                $table->foreignUuid('survey_responder_id')->references('id')->on('survey_responders');
                $table->foreignUuid('user_id')->references('id')->on('users');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_question_answers');
        }
    };
