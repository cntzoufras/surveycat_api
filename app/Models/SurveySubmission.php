<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    
    class SurveySubmission extends Model {
        
        use HasFactory;
        
        protected $guarded  = ['uuid'];
        protected $fillable = ['pages', 'uv', 'pv', 'amt', 'name', 'description', 'style_options'];
        
        
        public function survey(): BelongsTo {
            return $this->belongsTo(Survey::class);
        }
        
        public function respondent(): BelongsTo {
            return $this->belongsTo(Respondent::class);
        }
        
        public function survey_questions(): HasMany {
            return $this->hasMany(SurveyQuestion::class);
        }
    }
