<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use JeffGreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use SoftDeletes;
    use HasApiTokens;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use Notifiable;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'guardian_email',
        'guardian_phone',
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
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessFilament(): bool
    {
        return true;
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function routeNotificationForMail(Notification $notification): array
    {
        $emails = [
            $this->email,
            $this->alternate_email,
            $this->guardian_email,
        ];

        return array_filter($emails, fn ($value) => ! is_null($value) && $value !== '');
    }

    public function getUnpaidLectures(): Collection|array
    {
        return Attendance::query()->wherePaid(false)->whereHas('enrollment', function ($query) {
            $query->where('user_id', $this->id);
        })->get();
    }

    public function getUnpaidLecturesForSection(Section $section): Collection|array
    {
        return Attendance::query()->wherePaid(false)->whereHas('enrollment', function ($query) use ($section) {
            $query->where('user_id', $this->id)->where('section_id', $section->id);
        })->get();
    }
}
