<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    
    class QuestionType extends Model {
        
        use HasFactory;
        
        public $timestamps = false;
        
        protected $guarded = ['id', 'title', 'description'];
        
        protected $fillable = [''];
        
        public function survey_questions(): belongsToMany {
            return $this->belongsToMany(SurveyQuestion::class);
        }
        
        public function library_question() {
            return $this->belongsToMany(LibraryQuestion::class);
        }
    }
