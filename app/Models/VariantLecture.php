<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantLecture extends Model
{
    use HasFactory;

    public function variant()
    {
        return $this->belongsTo(CourseVariant::class);
    }
}
