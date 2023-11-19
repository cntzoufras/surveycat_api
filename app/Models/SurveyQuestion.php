<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyQuestion extends Model {

    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $fillable = [
        'title', 'is_required', 'is_public', 'style_id', 'question_tags', 'views',
    ];

    /**
     * Get the survey page it belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey_page(): BelongsTo {
        return $this->belongsTo(SurveyPage::class);
    }

    /**
     * Get the question type it has
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question_type(): BelongsTo {
        return $this->belongsTo(QuestionType::class);
    }

    /**
     * Get all tags associated to this survey question
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tags(): MorphMany {
        return $this->morphMany(Tag::class, 'taggable');
    }

    /**
     * Get all comments associated to this survey question
     */
    public function comments(): MorphMany {
        return $this->morphMany(Comment::class, 'commentable');
    }

}
