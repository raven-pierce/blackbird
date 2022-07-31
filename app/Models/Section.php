<?php

namespace App\Models;

use App\Enums\DeliveryMethod;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'course_id',
        'pricing_id',
        'start_day',
        'end_day',
        'delivery_method',
        'seats',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_day' => 'datetime',
        'end_day' => 'datetime',
        'delivery_method' => DeliveryMethod::class,
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }

    public function assistantships()
    {
        return $this->hasMany(Assistantship::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function isFull()
    {
        if ($this->enrollments->count() >= $this->seats) {
            return true;
        }

        return false;
    }

    public function generateLectures(int $day, int $startHour, int $startMinute, int $endHour, int $endMinute)
    {
        $period = CarbonPeriod::since($this->start_day)->until($this->end_day, true);

        $lectureDates = array_filter($period->toArray(), function ($date) use ($day) {
            return $date->dayOfWeek === $day;
        });

        foreach ($lectureDates as $date) {
            Lecture::create([
                'section_id' => $this->id,
                'start_time' => $date->copy()->setTime($startHour, $startMinute),
                'end_time' => $date->copy()->setTime($endHour, $endMinute),
            ]);
        }
    }

    public function getLecturesThisWeek()
    {
        return $this->lectures()->whereBetween('start_time', [today(), now()->endOfWeek()])->get();
    }

    public function getLecturesThisMonth()
    {
        return $this->lectures()->whereBetween('start_time', [today(), now()->endOfMonth()])->get();
    }

    public function getLecturesLeftInWeek(Carbon $startDate)
    {
        return $this->lectures()->whereBetween('start_time', [$startDate, $startDate->copy()->endOfWeek()])->get();
    }

    public function getLecturesInWeeks(int $weeks = 1)
    {
        return $this->lectures()->whereBetween('start_time', [today()->addWeeks($weeks)->startOfWeek(), today()->addWeeks($weeks)->endOfWeek()])->get();
    }
}
