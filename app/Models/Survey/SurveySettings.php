<?php

namespace App\Models\Survey;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveySettings extends Model {

    use HasFactory, Uuids;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded  = ['id'];
    protected $fillable = [
        'show_page_title', 'show_page_numbers', 'show_question_numbers',
        'show_progress_bar', 'required_asterisks', 'banner_image', 'survey_id',
    ];

    /**
     * Get all surveys that this setting is assigned to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey(): BelongsTo {
        return $this->belongsTo(Survey::class);
    }

    /**
     * Get default survey settings values
     *
     * @return array
     */
    public static function getDefaultSettings(): array
    {
        return [
            'show_page_title' => true,
            'show_page_numbers' => true,
            'show_question_numbers' => true,
            'show_progress_bar' => false,
            'required_asterisks' => true,
            'banner_image' => null,
        ];
    }

}
