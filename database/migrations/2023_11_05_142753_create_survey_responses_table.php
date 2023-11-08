<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_responses', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address');
                $table->string('device');
                $table->unsignedBigInteger('completion_time')->nullable();
                $table->timestamps();
                $table->foreignUuid('survey_id')->constrained('surveys');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('survey_responses', function (Blueprint $table) {
                $table->dropForeign('survey_responses_survey_id_foreign');
            });
            Schema::dropIfExists('survey_responses');
        }
    };
