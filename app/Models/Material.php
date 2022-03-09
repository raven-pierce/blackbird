<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Material
 *
 * @property int $id
 * @property int $course_id
 * @property string $file_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course $course
 * @method static \Illuminate\Database\Eloquent\Builder|Material newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Material newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Material query()
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereFileUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Material whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Material extends Model
{
    protected $fillable = [];

    protected $with = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
