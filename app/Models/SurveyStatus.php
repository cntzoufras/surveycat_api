<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use App\Traits\Uuids;
    use Illuminate\Notifications\Notifiable;
    
    class SurveyStatus extends Model {
        
        use HasFactory, Notifiable, Uuids;
        
        protected $guarded = ['id'];
        
        public function surveys(): BelongsToMany {
            return $this->belongsToMany(Survey::class);
        }
        
    }
