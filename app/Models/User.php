<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Paddle\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens, HasRoles, Billable, Notifiable;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'profile',
        'socialiteProfiles'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function socialiteProfiles()
    {
        return $this->hasMany(SocialiteProfile::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function assistantships()
    {
        return $this->hasMany(Assistantship::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function isTutor()
    {
        if ($this->hasRole('Tutor')) {
            return true;
        }

        return false;
    }

    public function isAssistant()
    {
        if ($this->hasRole('Assistant')) {
            return true;
        }

        return false;
    }

    public function isStudent()
    {
        if ($this->hasRole('Student')) {
            return true;
        }

        return false;
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
        return Enrollment::create(['section_id' => $section->id,
            'user_id' => $this->id,
        ]);
    }

    public function designateAssistantToSection(Section $section, User $user)
    {
        if (! $user->hasRole('Assistant')) {
            $user->syncRoles(['Assistant']);
        }

        return Assistantship::create(['section_id' => $section->id,
            'user_id' => $user->id,
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
