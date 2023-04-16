<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    
    class SurveySubmission extends Model {
        
        use HasFactory;
        
        protected $fillable = ['pages', 'uv', 'pv', 'amt'];
    }
