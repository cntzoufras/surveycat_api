<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_styles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('footer');
                $table->jsonb('settings'); // logo, fonts, primary_color, secondary_color
                $table->string('image');
                $table->boolean('is_default');
                $table->boolean('is_public');
                $table->boolean('is_archived');
                $table->timestamps();
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_styles');
        }
    };
