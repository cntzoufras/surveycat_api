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
                $table->uuid('id')->primary()->index();
                $table->unsignedBigInteger('completion_time')->nullable();
                $table->jsonb('response');
                $table->timestamps();
                $table->foreignUuId('survey_id')->references('id')->on('surveys')->onDelete('cascade');
                $table->foreignUuid('survey_respondent_id')->references('id')->on('survey_respondents');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('survey_submissions', function (Blueprint $table) {
                $table->dropForeign('survey_submissions_survey_respondent_id_foreign');
            });
            Schema::dropIfExists('survey_submissions');
        }
    };
