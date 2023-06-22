<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_pages', function (Blueprint $table) {
                $table->id();
                $table->string('survey_page_title');
                $table->string('survey_page_description')->nullable();
                $table->enum('align', ['left', 'center', 'right'])->default('left');
                $table->unsignedInteger('sort_index')->default(0)->index();
                $table->boolean('require_questions')->default(false);
                $table->timestamps();
                $table->foreignUuid('survey_id')->references('id')->on('surveys');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_pages');
        }
    };