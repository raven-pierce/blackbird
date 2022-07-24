<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Paddle\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Billable;

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

    public function toResource()
    {
        return new UserResource($this);
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
