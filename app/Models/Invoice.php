<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'user',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'external_id',
        'user_id',
        'invoice_url',
        'amount',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markExpired(): void
    {
        $this->status = 'Void';
        $this->save();

        $attendances = Attendance::query()->whereInvoiceId($this->id)->wherePaid(false)->get();
        $attendances->each(function (Attendance $attendance) {
            $attendance->invoice_id = null;
            $attendance->save();
        });
    }

    public function markPaid(): void
    {
        $this->status = 'Paid';
        $this->save();

        $attendances = Attendance::query()->whereInvoiceId($this->id)->wherePaid(false)->get();
        $attendances->each(function (Attendance $attendance) {
            $attendance->paid = true;
            $attendance->save();
        });
    }

    public function markUnpaid(): void
    {
        $this->status = 'Unpaid';
        $this->save();

        $attendances = Attendance::query()->whereInvoiceId($this->id)->wherePaid(true)->get();
        $attendances->each(function (Attendance $attendance) {
            $attendance->paid = false;
            $attendance->save();
        });
    }
}
