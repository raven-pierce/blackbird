<?php

namespace App\Models;

use App\Enums\LectureFrequency;
use App\Enums\Weekdays;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecture extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'section_id',
        'day',
        'start_time',
        'end_time',
        'start_day',
        'end_day',
        'frequency',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'day' => Weekdays::class,
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'start_day' => 'datetime',
        'end_day' => 'datetime',
        'frequency' => LectureFrequency::class,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'duration',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function duration(): Attribute
    {
        return Attribute::get(fn() => $this->start_time->diffInMinutes($this->end_time));
    }
}
