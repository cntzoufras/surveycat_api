<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyPage extends Model {

    use HasFactory, SoftDeletes;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded  = ['id'];
    protected $fillable = ['title', 'description', 'align', 'sort_index', 'require_questions', 'survey_id'];

    /**
     * Get the survey questions associated with the survey page.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function survey_questions(): HasMany {
        return $this->hasMany(SurveyQuestion::class);
    }

}
