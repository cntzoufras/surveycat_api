<?php

namespace App\Models\Survey;

use App\Models\Comment;
use App\Models\QuestionType;
use App\Models\Tag;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyQuestion extends Model {

    use HasFactory, Uuids, SoftDeletes;

    public       $incrementing = false;
    public mixed $is_required, $title, $question_type_id, $additional_settings, $survey_page_id;

    protected $keyType = 'string';

    protected $guarded  = ['id'];
    protected $fillable = [
        'title', 'is_required', 'question_tags', 'question_type_id', 'survey_page_id', 'views', 'additional_settings',
    ];

    protected $casts = [
        'additional_settings' => 'array',
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags(): MorphToMany {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get all comments associated to this survey question
     */
    public function comments(): MorphMany {
        return $this->morphMany(Comment::class, 'commentable');
    }

}
