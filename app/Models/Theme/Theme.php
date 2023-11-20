<?php

namespace App\Models\Theme;

use App\Models\Survey\Survey;
use App\Models\User;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Theme extends Model {

    use HasFactory, Uuids;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded = ['id'];

    protected $fillable = ['name', 'description', 'user_id', 'theme_setting_id'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function theme_setting(): BelongsTo {
        return $this->belongsTo(ThemeSetting::class, 'theme_setting_id', 'id');
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
