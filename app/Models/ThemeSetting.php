<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    
    class ThemeSetting extends Model {
        
        use HasFactory;
        
        protected $fillable = ['name', 'description',];
        
        protected $guarded = ['id'];
        
        public function theme(): BelongsToMany {
            return $this->belongsTomany(Theme::class);
        }
        
        public function theme_variable() {
            return $this->belongsTo(ThemeVariable::class);
        }
        
    }
