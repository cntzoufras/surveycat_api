<?php

namespace App\Models\Survey;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class SurveyStatus extends Model {

    use HasFactory, Notifiable, Uuids;

    protected $guarded = ['id'];

    /**
     * Returns the surveys that have this status
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function surveys(): HasMany {
        return $this->hasMany(Survey::class);
    }

}
