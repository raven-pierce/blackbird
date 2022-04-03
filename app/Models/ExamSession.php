<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'slug',
        'term',
        'session',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'session' => 'datetime',
    ];

    public function awardingBody()
    {
        return $this->belongsTo(AwardingBody::class);
    }

    public function courseLevels()
    {
        return $this->hasMany(CourseLevel::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
