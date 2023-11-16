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
                $table->uuid('id')->primary();
                $table->string('title')->index();
                $table->boolean('is_required');
                $table->foreignId('question_type_id')->constrained('question_types');
                $table->jsonb('additional_settings')->nullable();
                $table->foreignId('survey_page_id')->constrained('survey_pages');
                $table->timestamps();
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('survey_questions', function (Blueprint $table) {
                $table->dropForeign(['question_type_id']);
                $table->dropForeign(['survey_page_id']);
            });
            Schema::dropIfExists('survey_questions');
        }
    };
