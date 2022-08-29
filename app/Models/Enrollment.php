<?php

namespace App\Models;

use App\Jobs\SendInvoice;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use NumberFormatter;

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

    public function scopeTaughtBy(Builder $query, User $user): Builder
    {
        return $query->whereHas('section', function (Builder $query) use ($user) {
            $query->whereHas('course', function (Builder $query) use ($user) {
                $query->whereBelongsTo($user, 'tutor');
            });
        });
    }

    // TODO: Fix Scope for All Models
    public function scopeAttendedLecture(Builder $query, Lecture $lecture): Builder
    {
        return $query->whereHas('attendances', function (Builder $query) use ($lecture) {
            $query->whereHas('lecture', function (Builder $query) use ($lecture) {
                $query->whereKey($lecture->getKey());
            });
        });
    }

    public function unpaidAttendances()
    {
        return $this->attendances()->wherePaid(false);
    }

    public function paidAttendances()
    {
        return $this->attendances()->wherePaid(true);
    }

    public function generateInvoice(int $quantity = null, bool $override = false): void
    {
        $invoiceValue = $this->section->pricing->amount * ($quantity ?? $this->unpaid_attendances_count);
        $formattedInvoiceValue = NumberFormatter::create('en-US', NumberFormatter::CURRENCY)->formatCurrency($invoiceValue, config('payment.display_currency'));

        if (! $invoiceValue >= config('payment.payment_threshold') && ! $override) {
            Notification::make()
                ->title('Payment Threshold')
                ->body('The invoice value hasn\'t reached the minimum payment threshold.')
                ->danger()
                ->send();
        }

        if ($override && $invoiceValue === 0) {
            Notification::make()
                ->title('Invoice Value')
                ->body('The invoice value is zero.')
                ->danger()
                ->send();
        }

        $invoiceItems[] = [
            'ItemName' => $this->section->course->tutor->name.' - '.$this->section->pricing->name,
            'Quantity' => $quantity ?? $this->unpaid_attendances_count,
            'UnitPrice' => $this->section->pricing->amount,
        ];

        SendInvoice::dispatch($this->student, $invoiceItems);

        Notification::make()
            ->title('Invoice Generated')
            ->body('An invoice of value '.$formattedInvoiceValue.' has been generated successfully.')
            ->success()
            ->send();
    }
}
