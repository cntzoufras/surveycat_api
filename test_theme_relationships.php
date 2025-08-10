<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Theme\Theme;
use App\Models\Theme\ThemeSetting;
use App\Models\Theme\VariablePalette;

// Test 1: Get a theme by ID without relationships
echo "Test 1: Theme without relationships\n";
$theme = Theme::find('ca263921-6111-4d50-affb-5bda2b716d09');
if ($theme) {
    echo "Theme found: " . $theme->title . "\n";
    echo "Theme data: " . json_encode($theme->toArray(), JSON_PRETTY_PRINT) . "\n\n";
} else {
    echo "Theme not found\n\n";
}

// Test 2: Get a theme by ID with relationships
echo "Test 2: Theme with relationships\n";
$themeWithRelationships = Theme::with('theme_setting.variable_palettes')->find('ca263921-6111-4d50-affb-5bda2b716d09');
if ($themeWithRelationships) {
    echo "Theme found: " . $themeWithRelationships->title . "\n";
    echo "Theme data with relationships: " . json_encode($themeWithRelationships->toArray(), JSON_PRETTY_PRINT) . "\n\n";
    
    if ($themeWithRelationships->theme_setting) {
        echo "Theme setting found\n";
        echo "Theme setting data: " . json_encode($themeWithRelationships->theme_setting->toArray(), JSON_PRETTY_PRINT) . "\n\n";
        
        if ($themeWithRelationships->theme_setting->variable_palettes) {
            echo "Variable palettes found: " . $themeWithRelationships->theme_setting->variable_palettes->count() . "\n";
            echo "Variable palettes data: " . json_encode($themeWithRelationships->theme_setting->variable_palettes->toArray(), JSON_PRETTY_PRINT) . "\n\n";
        } else {
            echo "No variable palettes found\n\n";
        }
    } else {
        echo "No theme setting found\n\n";
    }
} else {
    echo "Theme with relationships not found\n\n";
}

// Test 3: Check if theme_setting exists for this theme
echo "Test 3: Check if theme_setting exists for this theme\n";
$themeSetting = ThemeSetting::where('theme_id', 'ca263921-6111-4d50-affb-5bda2b716d09')->first();
if ($themeSetting) {
    echo "Theme setting found for theme ID\n";
    echo "Theme setting data: " . json_encode($themeSetting->toArray(), JSON_PRETTY_PRINT) . "\n\n";
    
    // Check if variable palettes exist for this theme setting
    $variablePalettes = VariablePalette::where('theme_setting_id', $themeSetting->id)->get();
    echo "Variable palettes count: " . $variablePalettes->count() . "\n";
    echo "Variable palettes data: " . json_encode($variablePalettes->toArray(), JSON_PRETTY_PRINT) . "\n\n";
} else {
    echo "No theme setting found for theme ID\n\n";
}

// Test 4: Check all themes and their relationships
echo "Test 4: All themes\n";
$allThemes = Theme::all();
foreach ($allThemes as $theme) {
    echo "Theme: " . $theme->title . " (ID: " . $theme->id . ")\n";
    if ($theme->theme_setting) {
        echo "  Theme setting exists\n";
        if ($theme->theme_setting->variable_palettes) {
            echo "  Variable palettes count: " . $theme->theme_setting->variable_palettes->count() . "\n";
        } else {
            echo "  No variable palettes\n";
        }
    } else {
        echo "  No theme setting\n";
    }
}
