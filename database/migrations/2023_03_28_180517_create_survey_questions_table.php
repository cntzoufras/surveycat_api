<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_questions', function (Blueprint $table) {
                $table->uuid('id')->primary()->unique()->index();
                $table->string('title')->index();
                $table->boolean('is_required');
                $table->string('question_type');
                $table->jsonb('additional_settings')->nullable();
                $table->string('commentable_type')->nullable();
                $table->timestamps();
                $table->foreignId('commentable_id')->nullable()->constrained('comments');
                $table->foreignId('question_type_id')->constrained('question_types');
                $table->foreignId('survey_page_id')->constrained('survey_pages');
                $table->foreignId('taggable_id')->nullable()->constrained('tags');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('survey_questions', function (Blueprint $table) {
                $table->dropForeign('survey_questions_commentable_id_foreign');
                $table->dropForeign('survey_questions_taggable_id_foreign');
                $table->dropForeign('survey_questions_question_type_id_foreign');
                $table->dropForeign('survey_questions_survey_page_id_foreign');
            });
            Schema::dropIfExists('survey_questions');
        }
    };
