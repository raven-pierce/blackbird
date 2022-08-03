<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'section_id',
        'start_time',
        'end_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
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

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function recording()
    {
        return $this->hasOne(Recording::class);
    }

    public function duration(): Attribute
    {
        return Attribute::get(fn () => $this->start_time->diffInMinutes($this->end_time));
    }
}