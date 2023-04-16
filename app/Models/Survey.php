<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    
    class Survey extends Model {
        
        use HasFactory;
        
        public function survey_styles() {
            return $this->belongsToMany(SurveyStyle::class);
        }
        
        public function survey_submissions() {
            return $this->hasMany(SurveySubmission::class);
        }
    }
