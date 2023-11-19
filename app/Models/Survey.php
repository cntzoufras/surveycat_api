<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Survey extends Model {

    use HasFactory, Notifiable, Uuids, SoftDeletes;

    public $incrementing = false;

    protected $guarded = ['id'];

    protected $fillable = ['title', 'description', 'survey_category_id', 'survey_status_id'];

    protected $attributes = [
        'survey_status_id' => 1,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function theme(): hasOne {
        return $this->hasOne(Theme::class, 'theme_id', 'id');
    }

    /**
     * Get the submissions using this survey
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function survey_submissions(): HasMany {
        return $this->hasMany(SurveySubmission::class);
    }

    /**
     * Get the survey category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function survey_category(): HasOne {
        return $this->hasOne(SurveyCategory::class);
    }

    /**
     * Get the survey status
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function survey_status(): HasOne {
        return $this->hasOne(SurveyStatus::class);
    }

    /**
     * Get all tags associated to this survey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tags(): MorphMany {
        return $this->morphMany(Tag::class, 'taggable');
    }

    /**
     * Get all survey comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments(): MorphMany {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
