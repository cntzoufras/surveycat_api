<?php

namespace App\Models\Theme;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariablePalette extends Model {

    use HasFactory, Uuids;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded = ['id'];

    protected $fillable = ['answer_color', 'primary_accent', 'primary_background', 'question_color', 'secondary_accent',
        'secondary_background', 'title_color', 'theme_variable_id',
    ];

    /**
     * Get the theme variable associated with this palette.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function theme_variable(): BelongsTo {
        return $this->belongsTo(ThemeVariable::class, 'theme_variable_id');
    }
}
