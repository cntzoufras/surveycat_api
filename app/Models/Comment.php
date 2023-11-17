<?php
    
    namespace App\Models;
    
    use App\Traits\Uuids;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\MorphTo;
    use Illuminate\Database\Eloquent\SoftDeletes;
    
    class Comment extends Model {
        
        use HasFactory, Uuids, SoftDeletes;
        
        public    $incrementing = false;
        protected $keyType      = 'string';
        
        protected $guarded  = ['id'];
        protected $fillable = ['content', 'user_id'];
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\MorphTo
         */
        public function commentable(): MorphTo {
            return $this->morphTo();
        }
    }
