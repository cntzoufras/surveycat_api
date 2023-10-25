<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_categories_surveys', function (Blueprint $table) {
                $table->uuid('id');
                $table->foreignId('survey_category_id')->constrained('survey_categories');
                $table->foreignUuid('survey_id')->constrained('surveys');
                $table->timestamps();
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('survey_categories_surveys', function (Blueprint $table) {
                $table->dropForeign('survey_categories_surveys_survey_category_id_foreign');
                $table->dropForeign('survey_categories_surveys_survey_id_foreign');
            });
            Schema::dropIfExists('survey_categories_surveys');
        }
    };
