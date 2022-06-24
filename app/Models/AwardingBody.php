<?php

namespace App\Models;

use App\Http\Resources\AwardingBodyResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardingBody extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'slug',
        'name',
    ];

    public function examSessions()
    {
        return $this->hasMany(ExamSession::class);
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

    public function toResource()
    {
        return new AwardingBodyResource($this);
    }
}
