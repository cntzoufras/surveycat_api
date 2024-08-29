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
        Schema::create('survey_questions', function (Blueprint $table) {
            if (DB::connection()->getDriverName() === 'pgsql') {
                DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
            }
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('title')->index();
            $table->boolean('is_required')->default(false);
            $table->foreignId('question_type_id')->constrained('question_types');
            $table->foreignUuid('survey_page_id')->constrained('survey_pages')->onDelete('cascade');
            $table->jsonb('additional_settings')->nullable(); // color , align, font
            $table->jsonb('question_tags')->nullable(); // { tag1 , tag2, tagX }
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->dropForeign(['question_type_id']);
            $table->dropForeign(['survey_page_id']);
        });
        Schema::dropIfExists('survey_questions');
    }
};
