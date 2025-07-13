<?php

namespace App\Models;

use App\Models\Survey\SurveyResponse;
use App\Models\Survey\SurveySubmission;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Respondent extends Model
{

    use HasFactory, Uuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['id'];
    protected $fillable = ['email', 'age', 'gender'];

    /**
     * Get all survey submissions by this respondent
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function survey_submissions(): HasMany
    {
        return $this->hasMany(SurveySubmission::class);
    }

    /**
     * Get the survey response associated with the respondent.
     *
     * @return HasOne
     */
    public function survey_response(): HasOne
    {
        return $this->hasOne(SurveyResponse::class);
    }
}
