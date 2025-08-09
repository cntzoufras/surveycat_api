<?php

namespace App\Models;

use App\Models\Survey\Survey;
use App\Models\Theme\Theme;
use App\Models\Theme\ThemeSetting;
use App\Traits\Uuids;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * App\Models\User
 *
 * @property string $id
 * @property string $username
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $avatar
 * @property string $role
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Collection<int, Survey> $surveys
 * @property-read Collection<int, Theme> $themes
 * @property-read Collection<int, ThemeSetting> $theme_settings
 * @property-read DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read Collection<int, PersonalAccessToken> $tokens
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Uuids, HasApiTokens;

    /**
     * The "type" of the primary key ID.
     * Indicates that the IDs are not auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     * Using $fillable is more secure than $guarded.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'role',
        'avatar',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }

    public function themes(): HasMany
    {
        return $this->hasMany(Theme::class);
    }

    public function theme_settings(): HasMany
    {
        return $this->hasMany(ThemeSetting::class);
    }

    // These polymorphic relationships are less common on a User model.
    // You may want to review if they should be morphMany or another type.
    public function tags(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
