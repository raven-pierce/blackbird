<?php

namespace App\Models;

use Laravel\Paddle\Cashier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'section_id',
        'user_id',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function unpaidAttendances()
    {
        return $this->attendances()->wherePaid(false);
    }

    public function paidAttendances()
    {
        return $this->attendances()->wherePaid(true);
    }

    public function unitPricing()
    {
        return Cashier::productPrices($this->section->pricing->paddle_id)->first()->price()->net;
    }

    public function paddlePayLink(Int $quantity = null)
    {
        return $this->student->chargeProduct($this->section->pricing->paddle_id, [
            'quantity' => $quantity ?? $this->unpaidAttendances->count(),
            'return_url' => route('billing.index'),
        ]);
    }
}
