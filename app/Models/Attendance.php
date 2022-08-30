<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Attendance extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'enrollment_id',
        'lecture_id',
        'join_time',
        'leave_time',
        'duration',
        'invoice_id',
        'paid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'join_time' => 'datetime',
        'leave_time' => 'datetime',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    public function scopeTaughtBy(Builder $query, User $user): Builder
    {
        return $query->whereHas('lecture', function (Builder $query) use ($user) {
            $query->whereHas('section', function (Builder $query) use ($user) {
                $query->whereHas('course', function (Builder $query) use ($user) {
                    $query->whereBelongsTo($user, 'tutor');
                });
            });
        });
    }

    public function scopeStudentEnrolled(Builder $query, User $user): Builder
    {
        return $query->whereHas('enrollment', function (Builder $query) use ($user) {
            $query->whereBelongsTo($user, 'student');
        });
    }
}
