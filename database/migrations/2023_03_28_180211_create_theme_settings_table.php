<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('theme_settings', function (Blueprint $table) {
                $table->uuid('id')->primary()->unique();
                $table->string('title');
                $table->string('footer');
                $table->jsonb('settings'); // logo, fonts, primary_color, secondary_color
                $table->text('image');
                $table->boolean('is_public');
                $table->boolean('is_archived');
                $table->timestamps();
                $table->foreignUuid('user_id')->constrained('users');
                $table->foreignUuid('survey_id')->constrained('surveys');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('theme_settings', function (Blueprint $table) {
                $table->dropForeign('theme_settings_survey_id_foreign');
                $table->dropForeign('theme_settings_user_id_foreign');
            });
            Schema::dropIfExists('theme_settings');
        }
    };
