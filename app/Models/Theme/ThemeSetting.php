<?php

namespace App\Models\Theme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThemeSetting extends Model
{

    use HasFactory;

    protected $guarded = ['id'];
    protected $fillable = ['settings', 'theme_id'];

    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * Get the themes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class, 'theme_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function variable_palettes(): HasMany
    {
        return $this->hasMany(VariablePalette::class, 'theme_setting_id');
    }

}
