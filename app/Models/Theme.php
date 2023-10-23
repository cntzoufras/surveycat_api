<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    
    class Theme extends Model {
        
        use HasFactory;
        
        protected $guarded = ['id'];
        
        protected $fillable = [''];
        
        public function theme_setting(): BelongsTo {
            return $this->belongsTo(ThemeSetting::class);
        }
    }
