<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('themes', function (Blueprint $table) {
                $table->uuid('id')->primary()->unique()->index();
                $table->string('name');
                $table->string('description');
                $table->string('created_by');
                $table->timestamps();
                $table->foreignUuid('user_id')->constrained('users');
                $table->foreignUuid('theme_setting_id')->constrained('theme_settings');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('themes', function (Blueprint $table) {
                $table->dropForeign('themes_theme_setting_id_foreign');
                $table->dropForeign('themes_user_id_foreign');
            });
            Schema::dropIfExists('themes');
        }
    };
