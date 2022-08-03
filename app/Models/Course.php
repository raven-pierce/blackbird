<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Znck\Eloquent\Relations\BelongsToThrough as BelongsToThroughRelation;
use Znck\Eloquent\Traits\BelongsToThrough;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToThrough;
    use Searchable;

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

    public function awardingBody(): BelongsToThroughRelation
    {
        return $this->belongsToThrough(AwardingBody::class, [ExamSession::class, Level::class, Subject::class]);
    }

    public function examSession(): BelongsToThroughRelation
    {
        return $this->belongsToThrough(ExamSession::class, [Level::class, Subject::class]);
    }

    public function level(): BelongsToThroughRelation
    {
        return $this->belongsToThrough(Level::class, Subject::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function lectures(): HasManyThrough
    {
        return $this->hasManyThrough(Lecture::class, Section::class);
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assistants(): HasManyThrough
    {
        return $this->hasManyThrough(Assistantship::class, Section::class);
    }

    public function enrollments(): HasManyThrough
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
