<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('survey_question_choices', function (Blueprint $table) {
            $table->id();
            $table->string('content')->index();
            $table->integer('sort_index')->default(0);
            $table->foreignUuid('survey_question_id')->constrained('survey_questions');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('survey_question_choices', function (Blueprint $table) {
            $table->dropForeign(['survey_question_id']);
        });
        Schema::dropIfExists('survey_question_choices');
    }
};
