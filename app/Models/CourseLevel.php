<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CourseLevel
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $courses
 * @property-read int|null $courses_count
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLevel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLevel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLevel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CourseLevel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug', 'name'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
