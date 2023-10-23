<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use App\Traits\Uuids;
    use Illuminate\Notifications\Notifiable;
    
    class Survey extends Model {
        
        use HasFactory, Notifiable, Uuids;
        
        protected $guarded  = ['uuid'];
        protected $fillable = [''];
        
        public function theme(): BelongsTo {
            return $this->belongsTo(Theme::class);
        }
        
        public function survey_submissions(): HasMany {
            return $this->hasMany(SurveySubmission::class);
        }
        
        
        public function survey_category(): HasOne {
            return $this->hasOne(SurveyCategory::class);
        }
        
        public function survey_status(): HasOne {
            return $this->hasOne(SurveyStatus::class);
        }
    }
