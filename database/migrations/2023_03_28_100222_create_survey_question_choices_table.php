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
        Schema::create('survey_question_choices', function (Blueprint $table) {
            if (DB::connection()->getDriverName() === 'pgsql') {
                DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
            }
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('content')->index();
            $table->integer('sort_index')->default(0);
            $table->foreignUuid('survey_question_id')->constrained('survey_questions')->onDelete('cascade');
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
