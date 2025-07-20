<?php

namespace App\Models;

use App\Models\Survey\SurveyQuestion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionType extends Model
{

    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    /**
     * Get all survey questions using this question type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function survey_questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class);
    }

}
