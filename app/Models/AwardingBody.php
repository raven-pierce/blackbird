<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AwardingBody
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $courses
 * @property-read int|null $courses_count
 * @method static \Illuminate\Database\Eloquent\Builder|AwardingBody newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AwardingBody newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AwardingBody query()
 * @method static \Illuminate\Database\Eloquent\Builder|AwardingBody whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AwardingBody whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AwardingBody whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AwardingBody whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AwardingBody whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AwardingBody extends Model
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
