<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Paddle\Billable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory;
    use SoftDeletes;
    use HasApiTokens;
    use TwoFactorAuthenticatable;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;
    use Billable;
    use Searchable;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'profile',
        'socialiteProfiles',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function canAccessFilament(): bool
    {
        return $this->email === 'icarus@blackbird.io' && $this->hasVerifiedEmail();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->profile_photo_url;
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function socialiteProfiles(): HasMany
    {
        return $this->hasMany(SocialiteProfile::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function isEnrolledInCourse(Course $course)
    {
        $sections = $course->section->pluck('id');

        return $this->enrollments()->whereIn('section_id', $sections)->exists();
    }

    public function isEnrolledInSection(Section $section)
    {
        return $this->enrollments()->where('section_id', $section->id)->exists();
    }

    public function enrollInSection(Section $section)
    {
        return Enrollment::create([
            'section_id' => $section->id,
            'user_id' => $this->id,
        ]);
    }

    public function getUnpaidLectures()
    {
        return Attendance::query()->wherePaid(false)->whereHas('enrollment', function ($query) {
            $query->where('user_id', $this->id);
        })->get();
    }

    public function getUnpaidLecturesForSection(Section $section)
    {
        return Attendance::query()->wherePaid(false)->whereHas('enrollment', function ($query) use ($section) {
            $query->where('user_id', $this->id)->where('section_id', $section->id);
        })->get();
    }
}
