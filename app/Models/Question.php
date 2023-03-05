<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    
    class Question extends Model {
        
        use HasFactory;
        
        protected $fillable = ['title', 'format_id', 'is_public', 'style_id', 'status', 'question_tags',
            'views',
        ];
    }
