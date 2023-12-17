<?php

namespace App\Models\Theme;

use App\Models\Survey\Survey;
use App\Models\User;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Theme extends Model {

    use HasFactory, Uuids;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded  = ['id'];
    protected $fillable = ['title', 'description', 'footer', 'user_id'];

    /**
     * Get the user that created this survey
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the theme settings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function theme_setting(): HasOne {
        return $this->hasOne(ThemeSetting::class, 'theme_setting_id', 'id');
    }

    /**
     * Get the theme settings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function theme_variable(): HasOne {
        return $this->hasOne(ThemeVariable::class, 'theme_variable_id', 'id');
    }

    /**
     * Get the surveys using this theme
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function survey(): BelongsToMany {
        return $this->belongsToMany(Survey::class);
    }

}
