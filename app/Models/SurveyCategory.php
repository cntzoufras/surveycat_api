<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\MorphToMany;
    
    class SurveyCategory extends Model {
        
        use HasFactory;
        
        protected $guarded  = [''];
        protected $fillable = ['title', 'description'];
        
        public function index() {
        
        }
        
        public function survey(): BelongsTo {
            return $this->belongsTo(Survey::class);
        }
        
        public function tags(): MorphToMany {
            return $this->morphToMany(Tag::class, 'taggable');
        }
        
    }
