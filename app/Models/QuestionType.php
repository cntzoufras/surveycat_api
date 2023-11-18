<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionType extends Model {

    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    /**
     * Get all survey questions using this question type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function survey_questions(): HasMany {
        return $this->hasMany(SurveyQuestion::class);
    }

    /**
     * Get all library questions using this question type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function library_question(): HasMany {
        return $this->hasMany(LibraryQuestion::class);
    }
}
