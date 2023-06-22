<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_question_tags', function (Blueprint $table) {
                $table->id();
                $table->jsonb('tags')->index();
                $table->timestamps();
                $table->foreignUuid('survey_question_id')->references('id')->on('survey_questions')->onDelete('cascade');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_question_tags');
        }
    };