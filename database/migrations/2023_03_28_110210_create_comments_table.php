<?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration {
        
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('comments', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('content');
                $table->foreignUuid('user_id')->constrained('users');
                $table->uuid('commentable_id')->nullable();
                $table->string('commentable_type')->nullable();
                $table->timestamps();
            });
        }
        
        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropForeign('comments_user_id_foreign');
            });
            Schema::dropIfExists('comments');
        }
    };
