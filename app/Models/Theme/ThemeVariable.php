<?php

namespace App\Models\Theme;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThemeVariable extends Model {

    use HasFactory;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded  = ['id'];
    protected $fillable = ['primary_background_alpha', 'theme_thumb', 'theme_id'];

    /**
     * Get the theme associated with this variable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function theme(): BelongsTo {
        return $this->belongsTo(Theme::class, 'theme_id', 'id');
    }
}
