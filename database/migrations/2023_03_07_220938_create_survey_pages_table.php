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
                $table->enum('pages', ['Page A', 'Page B', 'Page C', 'Page D', 'Page E', 'Page F'])->nullable();
                $table->integer('uv');
                $table->integer('pv');
                $table->integer('amt');
                $table->timestamps();
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_pages');
        }
    };
