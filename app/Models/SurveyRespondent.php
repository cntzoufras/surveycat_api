<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    
    class SurveyRespondent extends Model {
        
        use HasFactory;
        
        protected $guarded  = ['uuid'];
        protected $fillable = ['pages', 'uv', 'pv', 'amt', 'name', 'description', 'style_options'];
        
        public function survey_submissions(): HasMany {
            return $this->hasMany(SurveySubmission::class);
        }
    }
