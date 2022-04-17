<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVariant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'course_id',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var string[]
     */
    protected $with = ['variantLectures'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function assistants()
    {
        return $this->belongsToMany(User::class, 'course_assistants');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_students');
    }

    public function variantLectures()
    {
        return $this->hasMany(VariantLecture::class);
    }
}
