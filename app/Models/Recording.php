<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Recording extends Model
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
        'lecture_id',
        'azure_item_id',
        'file_name',
        'file_path',
    ];

    public function lecture(): BelongsTo
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
        return $query->whereHas('lecture', function (Builder $query) use ($user) {
            $query->whereHas('section', function (Builder $query) use ($user) {
                $query->whereHas('enrollments', function (Builder $query) use ($user) {
                    $query->whereBelongsTo($user, 'student');
                });
            });
        });
    }
}
