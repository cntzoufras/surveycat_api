<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('theme_variables', function (Blueprint $table) {
                $table->uuid('id')->primary()->unique();
                $table->string('default_palette');
                $table->string('layout_applied');
                $table->string('primary_background_alpha');
                $table->string('theme_thumb');
                $table->timestamps();
                $table->foreignUuid('theme_id')->constrained('themes');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('theme_variables', function (Blueprint $table) {
                $table->dropForeign('theme_variables_theme_id_foreign');
            });
            Schema::dropIfExists('theme_variables');
        }
    };
