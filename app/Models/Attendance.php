<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'enrollment_id',
        'section_id',
        'join_time',
        'leave_time',
        'duration',
        'paid',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'join_time' => 'datetime',
        'leave_time' => 'datetime',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function markAsPaid()
    {
        return $this->paid = true;
    }

    public function markAsUnpaid()
    {
        return $this->paid = false;
    }
}
