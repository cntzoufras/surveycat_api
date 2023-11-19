<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Uuids;

class User extends Authenticatable {

    use HasFactory, Notifiable, Uuids;

    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $guarded = ['id'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password', 'first_name', 'middle_name', 'last_name'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function surveys(): HasMany {
        return $this->hasMany(Survey::class);
    }

    public function themes(): HasMany {
        return $this->hasMany(Theme::class);
    }

    public function theme_settings(): HasMany {
        return $this->hasMany(ThemeSetting::class);
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\MorphTo {
        return $this->morphTo(Tag::class, 'taggable');
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphTo {
        return $this->morphTo(Comment::class, 'commentable');
    }

    public function survey_teplates(): HasMany {
        return $this->hasMany(SurveyTemplate::class);
    }


}
