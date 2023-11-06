<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_settings', function (Blueprint $table) {
                $table->id();
                $table->string('page_title');
                $table->boolean('show_page_title')->default(true);
                $table->boolean('show_page_numbers')->default(true);
                $table->boolean('show_question_numbers')->default(true);
                $table->boolean('show_progress_bar')->default(false);
                $table->boolean('required_asterisks')->default(true);
                $table->string('public_link')->nullable()->default(null);
                $table->string('banner_image')->nullable()->default(null);
                $table->timestamps();
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_settings');
        }
    };
