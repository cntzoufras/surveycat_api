<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('surveys', function (Blueprint $table) {
                $table->uuid('id')->primary()->unique();
                $table->string('title');
                $table->string('description');
                $table->timestamps();
                $table->integer('views')->default(0);
                $table->foreignId('survey_category_id')->constrained('survey_categories');
                $table->foreignId('survey_status_id')->constrained('survey_statuses');
                $table->index(['id', 'title']);
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('surveys', function (Blueprint $table) {
                $table->dropForeign('surveys_survey_category_id_foreign');
                $table->dropForeign('surveys_survey_status_id_foreign');
            });
            Schema::dropIfExists('surveys');
        }
    };
