<?php
    
    namespace App\Models;
    
    use App\Traits\Uuids;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\MorphTo;
    
    class Comment extends Model {
        
        use HasFactory, Uuids;
        
        protected $fillable     = ['body', 'commentable_type', 'user_id'];
        protected $guarded      = ['id'];
        public    $incrementing = false;
        protected $keyType      = 'string';
        
        public function commentable(): MorphTo {
            return $this->morphTo();
        }
    }
