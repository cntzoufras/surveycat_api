<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model {

    use HasFactory, Uuids;

    protected $guarded = ['id'];

    protected $fillable = ['content', 'user_id'];

    /**
     * Get all surveys that are assigned this tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function surveys(): MorphToMany {
        return $this->morphedByMany(Survey::class, 'taggable');
    }

    /**
     * Get all survey questions that are assigned this tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function survey_questions(): MorphToMany {
        return $this->morphedByMany(SurveyQuestion::class, 'taggable');
    }

    /**
     * Get all library questions that are assigned this tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function library_questions(): MorphToMany {
        return $this->morphedByMany(LibraryQuestion::class, 'taggable');
    }

    /**
     * Get all survey categories that are assigned this tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function survey_categories(): MorphToMany {
        return $this->morphedByMany(SurveyCategory::class, 'taggable');
    }
}
