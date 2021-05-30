<?php

namespace App\Models;

use App\Jobs\CheckMailjetContact;
use App\Jobs\UpdateMailjetContact;
use App\Policies\TextGenerationPolicy;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spark\Billable;
use Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use Billable, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'photo_s3_key',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (!$user->api_token) {
                $user->api_token = Str::random(80);
            }
        });

        static::created(function ($user) {
            dispatch(new CheckMailjetContact($user));
        });

        static::deleting(function ($user) {
            dispatch(new UpdateMailjetContact(null, $user->email, true));
        });

        static::updated(function ($user) {
            if ($user->mailjet_id) {
                dispatch(new UpdateMailjetContact($user));
            } else {
                dispatch(new CheckMailjetContact($user));
            }
        });
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function monthly_results()
    {
        return $this->results()
            ->where('created_at', '>=', now()->setDay(1)->setHour(0)->setMinute(0)->setSecond(0));
    }

    public function getMonthlyTokensAttribute()
    {
        return Result::where('user_id', $this->id)
            ->where('created_at', '>=', now()->setDay(1)->setHour(0)->setMinute(0)->setSecond(0))
            ->sum('user_tokens');
    }

    public function getIsAdminAttribute()
    {
        return $this->role === 'admin';
    }

    public function getTrialDaysLeftAttribute()
    {
        return max(0, TextGenerationPolicy::MAX_FREE_DAYS - now()->diffInDays($this->email_verified_at));
    }

    public function getPhotoUrlAttribute()
    {
        if (!$this->photo_s3_key) {
            return 'https://eu.ui-avatars.com/api/?background=0569A0&size=256&color=fff&name=' . str_replace(' ', '+', $this->name);
        }

        return Storage::temporaryUrl($this->photo_s3_key, now()->addHours(24));
    }
}
