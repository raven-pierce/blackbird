<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function teachesCourses()
    {
        return $this->hasMany(Course::class);
    }

    public function assistsCourses()
    {
        return $this->hasMany(Assistantship::class);
    }

    public function attendsCourses()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function isEnrolledInCourse(Course $course)
    {
        $courseVariants = $course->courseVariants->pluck('id');

        return $this->attendsCourses()->whereIn('course_variant_id', $courseVariants)->exists();
    }

    public function isEnrolledInCourseVariant(CourseVariant $courseVariant)
    {
        return $this->attendsCourses()->where('course_variant_id', $courseVariant->id)->exists();
    }

    public function enrollInCourseVariant(CourseVariant $courseVariant)
    {
        return Enrollment::create([
            'course_variant_id' => $courseVariant->id,
            'user_id' => $this->id,
        ]);
    }

    public function designateAssistantToCourseVariant(CourseVariant $courseVariant, User $user)
    {
        if (! $user->hasRole('Assistant')) {
            $user->syncRoles(['Assistant']);
        }

        return Assistantship::create([
            'course_variant_id' => $courseVariant->id,
            'user_id' => $user->id,
        ]);
    }
}
