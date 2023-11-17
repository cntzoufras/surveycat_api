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
                $table->uuid('id')->primary();
                $table->string('title');
                $table->string('description')->nullable();
                $table->timestamps();
                $table->integer('views')->default(0);
                $table->softDeletes();
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
                $table->dropForeign(['survey_category_id']);
                $table->dropForeign(['survey_status_id']);
            });
            Schema::dropIfExists('surveys');
        }
    };
