<?php

namespace App\Models\Survey;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class SurveyPage extends Model {

    use HasFactory, Notifiable, SoftDeletes, Uuids;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded  = ['id'];
    protected $fillable = ['title', 'description', 'layout', 'sort_index', 'require_questions', 'survey_id'];

    /**
     * Get the survey questions associated with the survey page.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function survey_questions(): HasMany {
        return $this->hasMany(SurveyQuestion::class);
    }

}
