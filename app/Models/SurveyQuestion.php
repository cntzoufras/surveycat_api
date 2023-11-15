<?php
    
    namespace App\Models;
    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Illuminate\Database\Eloquent\Relations\MorphMany;
    
    class SurveyQuestion extends Model {
        
        use HasFactory;
        
        protected $guarded = ['id'];
        
        protected $fillable = [
            'title', 'is_required', 'is_question_bank', 'format_id', 'is_public', 'style_id', 'question_tags',
            'views',
        ];
        
        public function survey_page(): BelongsTo {
            return $this->belongsTo(SurveyPage::class);
        }
        
        public function question_type(): HasOne {
            return $this->hasOne(QuestionType::class);
        }
        
        public function theme(): hasMany {
            return $this->hasMany(Theme::class);
        }
        
        public function tags(): MorphMany {
            return $this->morphMany(Tag::class, 'taggable');
        }
        
        /**
         * Get all of the post's comments.
         */
        public function comments(): MorphMany {
            return $this->morphMany(Comment::class, 'commentable');
        }
        
    }
