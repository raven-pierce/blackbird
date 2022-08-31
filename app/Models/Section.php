<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'course_id',
        'pricing_id',
        'code',
        'start_day',
        'end_day',
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
        'start_day' => 'datetime',
        'end_day' => 'datetime',
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

    public function generateLectures(int $day, string $startTime, string $endTime)
    {
        $period = CarbonPeriod::since($this->start_day)->until($this->end_day, true);

        $lectureDates = array_filter($period->toArray(), function ($date) use ($day) {
            return $date->dayOfWeek === $day;
        });

        foreach ($lectureDates as $date) {
            Lecture::create([
                'section_id' => $this->id,
                'start_time' => $date->copy()->setTimeFromTimeString($startTime),
                'end_time' => $date->copy()->setTimeFromTimeString($endTime),
            ]);
        }

        Notification::make()
            ->title('Lectures Generated')
            ->success()
            ->send();
    }

    public function getLecturesBetween(Carbon $start, Carbon $end): Collection|array
    {
        return $this->lectures()->whereBetween('start_time', [$start, $end])->get();
    }

    public function getLecturesThisWeek(): Collection|array
    {
        return $this->getLecturesBetween(now(), today()->endOfWeek());
    }

    public function getLecturesThisMonth(): Collection|array
    {
        return $this->getLecturesBetween(now(), today()->endOfMonth());
    }

    public function getLecturesLeftInWeek(Carbon $startDate): Collection|array
    {
        return $this->getLecturesBetween($startDate, $startDate->copy()->endOfWeek());
    }

    public function getLecturesInWeeks(int $weeks = 1): Collection|array
    {
        return $this->getLecturesBetween(today()->addWeeks($weeks)->startOfWeek(), today()->addWeeks($weeks)->endOfWeek());
    }

    public function getEarliestLectures(): Collection|array
    {
        $earliestLectures = $this->lectures()->whereTime('start_time', '>=', today());

        return $this->getLecturesLeftInWeek($earliestLectures->exists() ? $earliestLectures->first()->start_time : now());
    }
}
