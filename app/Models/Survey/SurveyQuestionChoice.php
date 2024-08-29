<?php

namespace App\Models\Survey;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyQuestionChoice extends Model {

    use HasFactory, Uuids, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = ['id'];

    protected $fillable = ['content', 'survey_question_id', 'sort_index'];

    /**
     * Get all survey questions this theme is associated to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey_question(): BelongsTo {
        return $this->belongsTo(SurveyQuestion::class);
    }
}
