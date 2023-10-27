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
                $table->uuid('id')->primary()->index();
                $table->string('title');
                $table->string('page_title');
                $table->boolean('show_page_title')->default(true);
                $table->boolean('show_page_numbers')->default(true);
                $table->boolean('show_question_numbers')->default(true);
                $table->boolean('show_progress_bar')->default(false);
                $table->boolean('required_asterisks')->default(true);
                $table->string('public_link')->nullable()->default(null);
                $table->string('banner_image')->nullable()->default(null);
                $table->bigInteger('views')->default(0);
                $table->timestamps();
                $table->boolean('is_template');
                $table->foreignId('survey_category_id')->constrained('survey_categories');
                $table->foreignId('survey_status_id')->constrained('survey_statuses');
                $table->uuid('survey_template_id')->nullable();
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
