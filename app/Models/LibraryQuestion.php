<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Illuminate\Database\Eloquent\Relations\MorphMany;
    
    class LibraryQuestion extends Model {
        
        use HasFactory;
        
        public $timestamps = false;
        
        public function survey_question(): BelongsTo {
            return $this->belongsTo(SurveyQuestion::class);
        }
        
        public function question_type(): hasOne {
            return $this->hasOne(QuestionType::class);
        }
        
        public function theme(): hasMany {
            return $this->hasMany(Theme::class);
        }
        
        public function tags() {
            return $this->morphMany(Tag::class, 'taggable');
        }
        
        /**
         * Get all of the post's comments.
         */
        public function comments(): MorphMany {
            return $this->morphMany(Comment::class, 'commentable');
        }
    }
