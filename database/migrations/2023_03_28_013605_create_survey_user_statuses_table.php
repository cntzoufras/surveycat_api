<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('survey_user_statuses', function (Blueprint $table) {
                $table->id();
                $table->uuid('user_id');
                $table->string('title')->index();
                $table->string('description');
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users');
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('survey_user_statuses');
        }
    };
