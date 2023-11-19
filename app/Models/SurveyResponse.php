<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model {

    use HasFactory, Uuids;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded = ['id'];

    protected $fillable = ['survey_id', 'survey_submission_id', 'respondent_id'];

    /**
     * Returns the used survey
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey(): BelongsTo {
        return $this->belongsTo(Survey::class);
    }

    /**
     * Returns the submission data
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey_submission(): BelongsTo {
        return $this->belongsTo(SurveySubmission::class);
    }

    /**
     *
     * Returns the respondent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function respondent(): BelongsTo {
        return $this->belongsTo(Respondent::class);
    }
}
