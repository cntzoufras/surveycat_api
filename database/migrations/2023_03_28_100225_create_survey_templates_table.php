<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up(): void {
            Schema::create('survey_templates', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->foreignUuid('survey_id')->constrained('surveys');
            });
        }
        
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down(): void {
            Schema::table('survey_templates', function (Blueprint $table) {
                $table->dropForeign(['survey_id']);
            });
            Schema::dropIfExists('survey_templates');
        }
    };
