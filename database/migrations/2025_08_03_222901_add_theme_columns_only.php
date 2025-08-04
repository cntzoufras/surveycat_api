<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add missing columns without foreign key constraints to avoid issues
        Schema::table('themes', function (Blueprint $table) {
            if (!Schema::hasColumn('themes', 'is_custom')) {
                $table->boolean('is_custom')->default(false);
            }
            
            if (!Schema::hasColumn('themes', 'base_theme_id')) {
                $table->uuid('base_theme_id')->nullable();
            }
            
            if (!Schema::hasColumn('themes', 'survey_id')) {
                $table->uuid('survey_id')->nullable();
            }
        });

        Schema::table('surveys', function (Blueprint $table) {
            if (!Schema::hasColumn('surveys', 'custom_theme_settings')) {
                $table->json('custom_theme_settings')->nullable();
            }
        });

        // Skip foreign key constraints to avoid migration conflicts
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            if (Schema::hasColumn('themes', 'is_custom')) {
                $table->dropColumn(['is_custom', 'base_theme_id', 'survey_id']);
            }
        });

        Schema::table('surveys', function (Blueprint $table) {
            if (Schema::hasColumn('surveys', 'custom_theme_settings')) {
                $table->dropColumn(['custom_theme_settings']);
            }
        });
    }
};
