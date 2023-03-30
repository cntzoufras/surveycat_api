<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    
    class SurveyStyle extends Model {
        
        use HasFactory;
        
        protected $table = 'survey_styles';
        
        protected $fillable = ['name', 'description', 'style_options'];
        
        protected $guarded = [''];
        
        public function surveys() {
            return $this->belongsToMany(Survey::class);
        }
    }
