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
        Schema::create('survey_pages', function (Blueprint $table) {
            if (DB::connection()->getDriverName() === 'pgsql') {
                DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
            }
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->enum('layout', ['single', 'multiple'])->default('multiple');
            $table->unsignedInteger('sort_index')->nullable()->default(0)->index();
            $table->boolean('require_questions')->default(false);
            $table->softDeletes();
            $table->timestamps();
            $table->foreignUuid('survey_id')->references('id')->on('surveys');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('survey_pages', function (Blueprint $table) {
            $table->dropForeign(['survey_id']);
        });
        Schema::dropIfExists('survey_pages');
    }
};
