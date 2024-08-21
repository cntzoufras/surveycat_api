<?php

namespace App\Models\Theme;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VariablePalette extends Model {

    use HasFactory, Uuids;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded  = ['id'];
    protected $fillable = [
        'title_color', 'question_color', 'answer_color',
        'primary_accent', 'primary_background',
        'secondary_accent', 'secondary_background',
        'theme_setting_id',
    ];

    /**
     * Get the theme variable associated with this palette.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function theme_settings(): BelongsToMany {
        return $this->belongsToMany(ThemeSetting::class);
    }

}
