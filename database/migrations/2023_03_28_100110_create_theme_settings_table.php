<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->jsonb('settings'); // logo, fonts, survey primary_background_alpha , theme_thumb
            $table->foreignUuid('theme_id')->nullable()->constrained('themes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('theme_settings', function (Blueprint $table) {
            $table->dropForeign(['theme_id']);
        });
        Schema::dropIfExists('theme_settings');
    }
};
