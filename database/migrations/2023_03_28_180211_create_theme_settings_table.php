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
                $table->uuid('id')->primary()->index();
                $table->string('title');
                $table->string('footer');
                $table->jsonb('settings'); // logo, fonts, primary_color, secondary_color
                $table->text('image');
                $table->boolean('is_public');
                $table->boolean('is_archived');
                $table->timestamps();
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('theme_settings');
        }
    };
