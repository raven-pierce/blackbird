<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Lecture extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'section_id',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'duration',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function recordings(): HasMany
    {
        return $this->hasMany(Recording::class);
    }

    public function duration(): Attribute
    {
        return Attribute::get(fn () => $this->start_date->diffInMinutes($this->end_date));
    }

    public function scopeTaughtBy(Builder $query, User $user): Builder
    {
        return $query->whereHas('section', function (Builder $query) use ($user) {
            $query->whereHas('course', function (Builder $query) use ($user) {
                $query->whereBelongsTo($user, 'tutor');
            });
        });
    }

    public function scopeStudentEnrolled(Builder $query, User $user): Builder
    {
        return $query->whereHas('section', function (Builder $query) use ($user) {
            $query->whereHas('enrollments', function (Builder $query) use ($user) {
                $query->whereBelongsTo($user, 'student');
            });
        });
    }

    public function scopeStudentAttended(Builder $query, Enrollment $enrollment): Builder
    {
        return $query->whereHas('attendances', function (Builder $query) use ($enrollment) {
            $query->whereBelongsTo($enrollment);
        });
    }
}
