<?php

namespace App\Models\Survey;

use App\Models\Respondent;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $ip_address
 */
class SurveyResponse extends Model {


    use HasFactory, Uuids;

    public       $incrementing = false;
    public mixed $respondent_id;
    protected    $keyType      = 'string';

    protected $guarded  = ['id'];
    protected $fillable = [
        'ip_address', 'device', 'session_id', 'survey_id', 'survey_submission_id', 'respondent_id',
        'country', 'timezone',
    ];

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
