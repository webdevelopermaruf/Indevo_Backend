<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'dob',
        'hobbies',
        'currency',
        'role',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'hobbies' => 'array',
        ];
    }

    protected $appends = ['age', 'preferences',  'is_reminder_alert', 'is_goal_deadline_alert', 'is_dark_mode', 'is_push_notification',];

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->dob)->age;
    }

    public function getPreferencesAttribute()
    {
        return json_decode($this->preference) ?? [];
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    protected function getPreferenceValue(string $key): bool
    {
        return json_decode($this->preference)->$key ?? false;
    }

    public function getIsReminderAlertAttribute(): bool
    {
        return $this->getPreferenceValue('reminder_alert');
    }

    public function getIsGoalDeadlineAlertAttribute(): bool
    {
        return $this->getPreferenceValue('goal_deadline_alert');
    }

    public function getIsDarkModeAttribute(): bool
    {
        return $this->getPreferenceValue('dark_mode');
    }

    public function getIsPushNotificationAttribute(): bool
    {
        return $this->getPreferenceValue('push_notification');
    }
}
