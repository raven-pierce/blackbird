<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Paddle\Cashier;
use Laravel\Scout\Searchable;

class Enrollment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'section_id',
        'user_id',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances(): HasMany
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
        return cache()->remember('pricing_'.$this->section->id, now()->addHour(), function () {
            return Cashier::productPrices($this->section->pricing->paddle_id)->first()->price()->net;
        });
    }

    public function paddlePayLink(int $quantity = null): string
    {
        return $this->student->chargeProduct($this->section->pricing->paddle_id, [
            'quantity' => $quantity ?? $this->unpaidAttendances->count(),
            'return_url' => route('billing.index'),
            'passthrough' => [
                'enrollment_id' => $this->id,
            ],
        ]);
    }
}
