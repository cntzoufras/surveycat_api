<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_question_choices', function (Blueprint $table) {
                $table->id();
                $table->string('title')->index();
                $table->foreignId('question_type_id')->constrained('question_types');
                $table->foreignUuid('survey_question_id')->constrained('survey_questions');
                $table->timestamps();
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('survey_question_choices', function (Blueprint $table) {
                $table->dropForeign('survey_question_choices_survey_question_id_foreign');
            });
            Schema::dropIfExists('survey_question_choices');
        }
    };