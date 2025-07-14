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
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->jsonb('settings')->comment('font_family_heading, font_family_body,
            background_image_url, logo_position, button_style (e.g., corner_radius, border_width),
            primary_background_alpha');
            $table->foreignUuid('theme_id')->constrained('themes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_settings', function (Blueprint $table) {
            $table->dropForeign(['theme_id']);
        });
        Schema::dropIfExists('theme_settings');
    }
};
