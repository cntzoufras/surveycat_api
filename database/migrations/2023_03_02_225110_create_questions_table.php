<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('questions', function (Blueprint $table) {
                $table->id();
                $table->ulid('format_id')->nullable();
                $table->ulid('is_public')->nullable();
                $table->ulid('style_id')->nullable();
                $table->string('status')->nullable();
                $table->jsonb('question_tags')->nullable();
                $table->bigInteger('views')->nullable();
                $table->timestamps();
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('questions');
        }
    };
