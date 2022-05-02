<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'awarding_body_id',
        'exam_session_id',
        'course_level_id',
        'subject_id',
        'user_id',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var string[]
     */
    protected $with = [
        'courseVariants',
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

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function courseVariants()
    {
        return $this->hasMany(CourseVariant::class);
    }

    public function variantLectures()
    {
        return $this->hasManyThrough(VariantLecture::class, CourseVariant::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assistants(): Attribute
    {
        return $this->hasManyThrough(Assistantship::class, CourseVariant::class);
    }

    public function enrollments()
    {
        return $this->hasManyThrough(Enrollment::class, CourseVariant::class);
    }

    public function seats(): Attribute
    {
        return Attribute::get(fn() => $this->courseVariants->pluck('seats')->sum());
    }

    public function isFull()
    {
        if ($this->enrollments->count() >= $this->seats) {
            return true;
        }

        return false;
    }
}
