<?php

namespace App\Models;

use App\Enums\Weekdays;
use App\Http\Resources\VariantLectureResource;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantLecture extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'course_variant_id',
        'day',
        'start_time',
        'end_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'day' => Weekdays::class,
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'duration',
    ];

    public function courseVariant()
    {
        return $this->belongsTo(CourseVariant::class);
    }

    public function toResource()
    {
        return new VariantLectureResource($this);
    }

    public function duration(): Attribute
    {
        return Attribute::get(fn() => $this->start_time->diffInMinutes($this->end_time));
    }
}
