<?php

namespace App\Models\Theme;

use App\Models\User;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ThemeSetting extends Model {

    use HasFactory, Uuids;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded  = ['id'];
    protected $fillable = ['settings', 'theme_id'];


    /**
     * Get the themes
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function theme(): BelongsTo {
        return $this->belongsTo(Theme::class, 'theme_id', 'id');
    }

    /**
     * Get the theme variable for this theme setting
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function theme_variable(): BelongsTo {
        return $this->belongsTo(ThemeVariable::class, 'theme_variable_id', 'id');
    }

}
