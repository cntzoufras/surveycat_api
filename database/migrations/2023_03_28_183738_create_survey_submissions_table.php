<?php
    
    use App\Models\Survey;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Str;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_submissions', function (Blueprint $table) {
                $table->uuid('id')->primary()->index();
                $table->string('user_type')->nullable();
                $table->unsignedBigInteger('completion_time')->nullable();
                $table->jsonb('results');
                $table->timestamps();
                $table->foreignUuId('survey_id')->references('id')->on('surveys')->onDelete('cascade');
                $table->foreignUuid('survey_respondent_id')->references('id')->on('survey_respondents');
                $table->foreignUuid('user_id')->references('id')->on('users');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_submissions');
        }
    };
