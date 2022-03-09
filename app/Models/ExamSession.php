<?php

namespace App\Models;

use App\Enums\SessionTerms;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\ExamSession
 *
 * @property int $id
 * @property string $slug
 * @property SessionTerms $term
 * @property Carbon $session
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $courses
 * @property-read int|null $courses_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSession whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSession whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSession whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamSession whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExamSession extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug', 'term', 'session'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'term' => SessionTerms::class,
        'session' => 'datetime'
    ];

    public function session(): Attribute
    {
        return Attribute::get(fn($value) => Carbon::parse($value));
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
