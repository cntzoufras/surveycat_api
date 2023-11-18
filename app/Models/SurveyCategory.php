<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class SurveyCategory extends Model {

    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['title', 'description'];

    public function surveys(): HasMany {
        return $this->hasMany(Survey::class);
    }

    public function tags(): MorphToMany {
        return $this->morphToMany(Tag::class, 'taggable');
    }

}
