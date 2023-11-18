<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('theme_variables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('primary_background_alpha');
            $table->string('theme_thumb'); // link to theme thumb png
            $table->foreignUuid('theme_id')->constrained('themes');
            $table->foreignUuid('variable_palette_id')->constrained('variable_palettes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('theme_variables', function (Blueprint $table) {
            $table->dropForeign(['theme_id']);
        });
        Schema::dropIfExists('theme_variables');
    }
};
