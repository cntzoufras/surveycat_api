<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Theme\Theme;
use App\Models\Theme\ThemeSetting;
use App\Models\Theme\VariablePalette;
use App\Models\Survey\Survey;

class ThemeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Heritage theme with proper theme settings
        $heritageTheme = Theme::firstOrCreate([
            'title' => 'Heritage',
            'description' => 'Classic heritage theme with orange accents',
            'is_custom' => false,
        ]);

        $heritageThemeSetting = ThemeSetting::firstOrCreate([
            'theme_id' => $heritageTheme->id,
        ], [
            'settings' => [
                'colors' => [
                    'primary' => '#FF5733',
                    'secondary' => '#333333',
                    'background' => '#FFFFFF',
                    'text' => '#000000',
                ],
                'typography' => [
                    'fontFamily' => 'Arial, sans-serif',
                    'fontSize' => '16px',
                ],
                'layout' => [
                    'maxWidth' => '800px',
                ],
            ],
        ]);

        VariablePalette::firstOrCreate([
            'theme_setting_id' => $heritageThemeSetting->id,
            'name' => 'Heritage Palette',
        ], [
            'title_color' => '#FF5733',
            'question_color' => '#000000',
            'answer_color' => '#4CAF50',
            'primary_accent' => '#FF5733',
            'primary_background' => '#FFFFFF',
            'secondary_accent' => '#333333',
            'secondary_background' => '#F5F5F5',
            'is_active' => true,
        ]);

        // Create Modern theme
        $modernTheme = Theme::firstOrCreate([
            'title' => 'Modern',
            'description' => 'Clean modern theme with blue accents',
            'is_custom' => false,
        ]);

        $modernThemeSetting = ThemeSetting::firstOrCreate([
            'theme_id' => $modernTheme->id,
        ], [
            'settings' => [
                'colors' => [
                    'primary' => '#2196F3',
                    'secondary' => '#757575',
                    'background' => '#FAFAFA',
                    'text' => '#212121',
                ],
                'typography' => [
                    'fontFamily' => 'Roboto, sans-serif',
                    'fontSize' => '16px',
                ],
                'layout' => [
                    'maxWidth' => '1000px',
                ],
            ],
        ]);

        VariablePalette::firstOrCreate([
            'theme_setting_id' => $modernThemeSetting->id,
            'name' => 'Modern Palette',
        ], [
            'title_color' => '#2196F3',
            'question_color' => '#212121',
            'answer_color' => '#4CAF50',
            'primary_accent' => '#2196F3',
            'primary_background' => '#FAFAFA',
            'secondary_accent' => '#757575',
            'secondary_background' => '#EEEEEE',
            'is_active' => true,
        ]);

        // Create Minimal theme
        $minimalTheme = Theme::firstOrCreate([
            'title' => 'Minimal',
            'description' => 'Minimalist theme with gray scale',
            'is_custom' => false,
        ]);

        $minimalThemeSetting = ThemeSetting::firstOrCreate([
            'theme_id' => $minimalTheme->id,
        ], [
            'settings' => [
                'colors' => [
                    'primary' => '#424242',
                    'secondary' => '#9E9E9E',
                    'background' => '#FFFFFF',
                    'text' => '#212121',
                ],
                'typography' => [
                    'fontFamily' => 'Helvetica, sans-serif',
                    'fontSize' => '16px',
                ],
                'layout' => [
                    'maxWidth' => '700px',
                ],
            ],
        ]);

        VariablePalette::firstOrCreate([
            'theme_setting_id' => $minimalThemeSetting->id,
            'name' => 'Minimal Palette',
        ], [
            'title_color' => '#424242',
            'question_color' => '#212121',
            'answer_color' => '#424242',
            'primary_accent' => '#424242',
            'primary_background' => '#FFFFFF',
            'secondary_accent' => '#9E9E9E',
            'secondary_background' => '#F5F5F5',
            'is_active' => true,
        ]);

        // Update any existing surveys to have proper theme relationships
        Survey::whereNull('theme_id')->update(['theme_id' => $heritageTheme->id]);
    }
}
