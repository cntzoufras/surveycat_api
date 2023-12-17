<?php

namespace App\Models;

use App\Models\Survey\SurveyResponse;
use App\Models\Survey\SurveySubmission;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Respondent extends Model {

    use HasFactory, Uuids;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded  = ['id'];
    protected $fillable = ['email', 'details'];

    /**
     * Get all survey submissions by this respondent
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function survey_submissions(): HasMany {
        return $this->hasMany(SurveySubmission::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function survey_responses(): HasMany {
        return $this->hasMany(SurveyResponse::class);
    }
}
