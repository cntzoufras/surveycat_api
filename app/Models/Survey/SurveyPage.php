<?php

namespace App\Models\Survey;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class SurveyPage extends Model {

    use HasFactory, Notifiable, SoftDeletes, Uuids;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded  = ['id'];
    protected $fillable = ['title', 'description', 'sort_index', 'require_questions', 'survey_id'];


    // Each survey page belongs to a single survey
    public function survey(): BelongsTo {
        return $this->belongsTo(Survey::class);
    }

    /**
     * Get the survey questions associated with the survey page.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function survey_questions(): HasMany {
        return $this->hasMany(SurveyQuestion::class);
    }

}
