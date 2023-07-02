<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Section extends Model
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
        'course_id',
        'pricing_id',
        'code',
        'start_date',
        'end_date',
        'delivery_method',
        'seats',
        'azure_team_id',
        'channel_folder',
        'recordings_folder',
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

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lectures(): HasMany
    {
        return $this->hasMany(Lecture::class);
    }

    public function recordings(): HasManyThrough
    {
        return $this->hasManyThrough(Recording::class, Lecture::class);
    }

    public function pricing(): BelongsTo
    {
        return $this->belongsTo(Pricing::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function scopeTaughtBy(Builder $query, User $user): Builder
    {
        return $query->whereHas('course', function (Builder $query) use ($user) {
            $query->whereBelongsTo($user, 'tutor');
        });
    }

    public function scopeStudentEnrolled(Builder $query, User $user): Builder
    {
        return $query->whereHas('enrollments', function (Builder $query) use ($user) {
            $query->whereBelongsTo($user, 'student');
        });
    }

    public function isFull(): bool
    {
        if ($this->enrollments->count() >= $this->seats) {
            return true;
        }

        return false;
    }

    public function generateLectures(int $day, string $startTime, string $endTime): void
    {
        $period = CarbonPeriod::since($this->start_date)->until($this->end_date, true);

        $lectureDates = array_filter($period->toArray(), function ($date) use ($day) {
            return $date->dayOfWeek === $day;
        });

        foreach ($lectureDates as $date) {
            Lecture::create([
                'section_id' => $this->id,
                'start_date' => $date->copy()->setTimeFromTimeString($startTime),
                'end_date' => $date->copy()->setTimeFromTimeString($endTime),
            ]);
        }

        Notification::make()
            ->title('Lectures Generated')
            ->success()
            ->send();
    }

    public function lecturesBetween(Carbon $start, Carbon $end): Builder
    {
        return $this->lectures()->whereBetween('start_date', [$start, $end])->getQuery();
    }

    public function assessmentsBetween(Carbon $start, Carbon $end): Builder
    {
        return $this->assessments()->whereBetween('release_date', [$start, $end])->getQuery();
    }
}
