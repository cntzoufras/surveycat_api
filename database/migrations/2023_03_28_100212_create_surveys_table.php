<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('surveys', function (Blueprint $table) {
            if (DB::connection()->getDriverName() === 'pgsql') {
                DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
            }
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('title')->nullable()->index();
            $table->string('description')->nullable();
            $table->foreignId('survey_category_id')->nullable()->constrained('survey_categories');
            $table->foreignId('survey_status_id')->nullable()->constrained('survey_statuses');
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignUuid('theme_id')->nullable()->constrained('themes');
            $table->string('public_link')->nullable()->default(null);
            $table->bigIncrements('views')->default(0);
            $table->integer('priority')->nullable()->default(null);
            $table->boolean('is_stock')->nullable()->default('false');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropForeign(['survey_category_id']);
            $table->dropForeign(['survey_status_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['theme_id']);
        });
        Schema::dropIfExists('surveys');
    }
};
