<?php
    
    use App\Models\Question;
    use App\Models\SurveyPage;
    use App\Models\SurveyQuestion;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_questions', function (Blueprint $table) {
                $table->uuid('id')->primary()->unique();
                $table->boolean('is_required');
                $table->string('title');
                $table->boolean('is_question_bank')->nullable()->default(false);
                $table->jsonb('question_tags')->nullable();
                $table->timestamps();
                $table->foreignIdFor(SurveyPage::class);
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_questions');
        }
    };
