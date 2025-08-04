<?php

namespace App\Models\Theme;

use App\Models\Survey\Survey;
use App\Models\User;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Theme extends Model
{
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['theme_setting'];

    use HasFactory, Uuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = [
        'is_custom' => 'boolean',
    ];

    protected $guarded = ['id'];
    protected $fillable = ['title', 'description', 'user_id', 'is_custom', 'base_theme_id', 'survey_id'];

    /**
     * Get the user that created this survey
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the theme settings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function theme_setting(): HasOne
    {
        return $this->hasOne(ThemeSetting::class, 'theme_id');
        // Explicitly specify foreign key to match themes.id (UUID) to theme_settings.theme_id (UUID)
    }

    /**
     * Get the surveys using this theme
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class, 'theme_id');
    }

}
