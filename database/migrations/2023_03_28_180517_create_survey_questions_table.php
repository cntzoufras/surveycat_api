<?php
    
    use App\Models\Question;
    use App\Models\SurveyPage;
    use App\Models\SurveyQuestion;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Str;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_questions', function (Blueprint $table) {
                $table->uuid('id')->primary()->index();
                $table->string('title');
                $table->boolean('is_required');
                $table->string('question_type');
                $table->jsonb('additional_settings')->nullable();
                $table->timestamps();
                $table->foreignIdFor(SurveyPage::class)->nullable();
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_questions');
        }
    };
