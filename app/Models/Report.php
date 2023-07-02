<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Report extends Model
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
        'enrollment_id',
        'start_date',
        'end_date',
        'remarks',
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

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function scopeTaughtBy(Builder $query, User $user): Builder
    {
        return $query->whereHas('enrollment', function (Builder $query) use ($user) {
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
