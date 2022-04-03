<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

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

    public function variants()
    {
        return $this->hasMany(CourseVariant::class);
    }

    public function lectures()
    {
        return $this->hasManyThrough(VariantLecture::class, CourseVariant::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class);
    }

    public function assistants(): Attribute
    {
        return Attribute::get(fn() => $this->load('variants.assistants')->timings->pluck('assistants')->flatten()->unique('id'));
    }

    public function students(): Attribute
    {
        return Attribute::get(fn() => $this->load('variants.students')->timings->pluck('students')->flatten()->unique('id'));
    }
}
