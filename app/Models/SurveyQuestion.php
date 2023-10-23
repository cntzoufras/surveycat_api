<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    
    class SurveyQuestion extends Model {
        
        use HasFactory;
        
        protected $guarded  = [];
        protected $fillable = [
            'title', 'is_required', 'is_question_bank', 'format_id', 'is_public', 'style_id', 'question_tags',
            'views',
        ];
        
    }
