<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'awarding_body_id',
        'exam_session_id',
        'level_id',
        'subject_id',
        'user_id',
    ];

    public function awardingBody()
    {
        return $this->belongsTo(AwardingBody::class);
    }

    public function examSession()
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function lectures()
    {
        return $this->hasManyThrough(Lecture::class, Section::class);
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assistants()
    {
        return $this->hasManyThrough(Assistantship::class, Section::class);
    }

    public function enrollments()
    {
        return $this->hasManyThrough(Enrollment::class, Section::class);
    }

    public function seats(): Attribute
    {
        return Attribute::get(fn () => $this->sections->pluck('seats')->sum());
    }

    public function isFull()
    {
        if ($this->enrollments->count() >= $this->seats) {
            return true;
        }

        return false;
    }
}
