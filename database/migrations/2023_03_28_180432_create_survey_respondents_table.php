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
            Schema::create('survey_respondents', function (Blueprint $table) {
                $table->uuid('id')->primary()->index();
                $table->uuid('survey_id');
                $table->string('email')->nullable();
                $table->jsonb('respondent_info'); // street_address, city, state, postal_code, country, age, gender, occupation, education_level
                $table->timestamps();
                $table->foreign('survey_id')->references('id')->on('surveys');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_respondents');
        }
    };
