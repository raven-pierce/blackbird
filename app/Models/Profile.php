<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'student_email',
        'phone',
        'guardian_email',
        'guardian_phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
