<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'tags',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
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

    public function enrollments(): HasManyThrough
    {
        return $this->hasManyThrough(Enrollment::class, Section::class);
    }

    protected function awardingBody(): Attribute
    {
        return Attribute::get(fn () => $this->tags->where('type', 'awarding_body')->first()->name ?? 'N/A');
    }

    protected function examSession(): Attribute
    {
        return Attribute::get(fn () => $this->tags->where('type', 'exam_session')->first()->name ?? 'N/A');
    }

    protected function courseLevel(): Attribute
    {
        return Attribute::get(fn () => $this->tags->where('type', 'course_level')->first()->name ?? 'N/A');
    }

    protected function subject(): Attribute
    {
        return Attribute::get(fn () => $this->tags->where('type', 'subject')->first()->name ?? 'N/A');
    }

    protected function seats(): Attribute
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
