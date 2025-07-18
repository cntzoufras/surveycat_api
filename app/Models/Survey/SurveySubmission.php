<?php

namespace App\Models\Survey;

use App\Models\Respondent;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveySubmission extends Model
{

    use HasFactory, Uuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['id'];
    protected $fillable = ['submission_data', 'survey_id', 'survey_response_id'];

    protected $casts = [
        'submission_data' => 'array',
    ];


    /**
     * Get the survey of this submission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * Get the response associated with this submission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey_response(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class);
    }

}
