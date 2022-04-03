<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVariant extends Model
{
    use HasFactory;

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

    public function lectures()
    {
        return $this->hasMany(VariantLecture::class);
    }
}
