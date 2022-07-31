<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'slug',
        'name',
    ];

    public function awardingBody()
    {
        return $this->belongsTo(AwardingBody::class);
    }

    public function examSession()
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
