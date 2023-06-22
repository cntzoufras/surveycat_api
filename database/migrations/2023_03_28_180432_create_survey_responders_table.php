<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_responders', function (Blueprint $table) {
                $table->uuid('id')->primary()->unique();
                $table->uuid('survey_id');
                $table->string('email')->nullable();
                $table->string('user_name');
                $table->jsonb('user_data'); // street_address, city, state, postal_code, country, age, gender, occupation, education_level
                $table->timestamps();
                $table->foreign('survey_id')->references('id')->on('surveys');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_responders');
        }
    };
