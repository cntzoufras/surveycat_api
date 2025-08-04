<?php

namespace App\Models\Survey;

use App\Models\Comment;
use App\Models\Tag;
use App\Models\Theme\Theme;
use App\Models\User;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Survey extends Model {

    use HasFactory, Notifiable, Uuids, SoftDeletes;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded  = ['id'];
    protected $fillable = [
        'title', 'description', 'survey_category_id', 'survey_status_id', 'user_id', 'theme_id', 'public_link', 'layout', 'custom_theme_settings'
    ];

    protected $casts = [
        'custom_theme_settings' => 'array',
    ];

    protected $attributes = [
        'survey_status_id' => 1,
    ];

    /**
     * Get the user that created this survey
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function theme(): BelongsTo {
        return $this->belongsTo(Theme::class);
    }

    /**
     * Get the survey category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey_category(): BelongsTo {
        return $this->belongsTo(SurveyCategory::class);
    }

    /**
     * Get the survey status
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey_status() {
        return $this->belongsTo(SurveyStatus::class);
    }

    /**
     * Get all tags associated to this survey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tags(): MorphToMany {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get all survey comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments(): MorphMany {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the submissions using this survey
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function survey_submissions(): HasMany {
        return $this->hasMany(SurveySubmission::class);
    }

    // Each survey has many pages
    public function survey_pages(): HasMany {
        return $this->hasMany(SurveyPage::class);
    }

    public function survey_settings(): HasOne {
        return $this->hasOne(SurveySettings::class);
    }

}
