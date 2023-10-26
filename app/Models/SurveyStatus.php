<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use App\Traits\Uuids;
    use Illuminate\Database\Eloquent\Relations\MorphMany;
    use Illuminate\Notifications\Notifiable;
    
    class SurveyStatus extends Model {
        
        use HasFactory, Notifiable, Uuids;
        
        protected $guarded  = ['id'];
        protected $fillable = [''];
        
        
        public function survey_submissions(): HasMany {
            return $this->hasMany(SurveySubmission::class);
        }
        
        public function surveys(): HasMany {
            return $this->hasMany(Survey::class);
        }
        
        
    }
