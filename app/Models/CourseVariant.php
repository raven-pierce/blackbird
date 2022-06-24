<?php

namespace App\Models;

use App\Enums\DeliveryMethod;
use App\Http\Resources\CourseVariantResource;
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
        'seats',
        'delivery_method',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'delivery_method' => DeliveryMethod::class,
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function variantLectures()
    {
        return $this->hasMany(VariantLecture::class);
    }

    public function assistantships()
    {
        return $this->hasMany(Assistantship::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function toResource()
    {
        return new CourseVariantResource($this);
    }

    public function isFull()
    {
        if ($this->enrollments->count() >= $this->seats) {
            return true;
        }

        return false;
    }
}
