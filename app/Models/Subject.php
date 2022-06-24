<?php

namespace App\Models;

use App\Http\Resources\SubjectResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
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

    public function awardingBody()
    {
        return $this->belongsTo(AwardingBody::class);
    }

    public function examSession()
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function courseLevel()
    {
        return $this->belongsTo(CourseLevel::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function toResource()
    {
        return new SubjectResource($this);
    }
}
