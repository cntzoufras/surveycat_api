<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class LibraryQuestion extends Model {

    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['title', 'description', 'question_type_id', 'additional_settings'];

    /**
     * Get the type of the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question_type(): BelongsTo {
        return $this->belongsTo(QuestionType::class);
    }

    /**
     * Get all tags associated to this library question
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags(): MorphToMany {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get the survey category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey_category(): BelongsTo {
        return $this->belongsTo(SurveyCategory::class);
    }
}
