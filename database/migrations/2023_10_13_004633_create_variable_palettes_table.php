<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('variable_palettes', function (Blueprint $table) {
                $table->uuid('id')->primary()->index();
                $table->string('answer_color');
                $table->string('primary_accent');
                $table->string('primary_background');
                $table->string('question_color');
                $table->string('secondary_accent');
                $table->string('secondary_background');
                $table->string('title_color');
                $table->timestamps();
                $table->foreignUuid('theme_variable_id')->constrained('theme_variables');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('variable_palettes', function (Blueprint $table) {
//                $table->dropForeign('theme_variable_id');
            });
            Schema::dropIfExists('variable_palettes');
        }
    };
