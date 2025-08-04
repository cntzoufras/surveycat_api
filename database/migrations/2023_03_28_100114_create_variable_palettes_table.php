<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('variable_palettes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->string('title_color');
            $table->string('question_color');
            $table->string('answer_color');
            $table->string('primary_accent');
            $table->string('primary_background');
            $table->string('secondary_accent');
            $table->string('secondary_background');
            $table->foreignId('theme_setting_id')->constrained('theme_settings')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variable_palettes');
    }
};
