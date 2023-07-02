<?php

namespace App\Models;

use App\Jobs\SendInvoice;
use Carbon\Carbon;
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

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function scopeTaughtBy(Builder $query, User $user): Builder
    {
        return $query->whereHas('section', function (Builder $query) use ($user) {
            $query->whereHas('course', function (Builder $query) use ($user) {
                $query->whereBelongsTo($user, 'tutor');
            });
        });
    }

    public function scopeAttendedLecture(Builder $query, Lecture $lecture): Builder
    {
        return $query->whereHas('attendances', function (Builder $query) use ($lecture) {
            $query->whereBelongsTo($lecture);
        });
    }

    public function unpaidAttendances(): Builder
    {
        return $this->attendances()->wherePaid(false);
    }

    public function paidAttendances(): Builder
    {
        return $this->attendances()->wherePaid(true);
    }

    public function generateInvoice(Carbon $startDate = null, Carbon $endDate = null, int $quantity = null): void
    {
        if ($startDate && $endDate) {
            $attendances = $this->attendances()
                ->whereBetween('join_date', [$startDate, $endDate])
                ->wherePaid(false)
                ->whereInvoiceId(null)
                ->get();
        } else {
            $attendances = $this->attendances()->wherePaid(false)->whereInvoiceId(null)->get();
        }

        $invoiceValue = $this->section->pricing->amount * ($quantity ?? $attendances->count());
        $formattedInvoiceValue = NumberFormatter::create('en-US', NumberFormatter::CURRENCY)->formatCurrency($invoiceValue, config('payment.display_currency'));

        if ($invoiceValue === 0) {
            Notification::make()
                ->title('Invoice Value')
                ->body('The invoice value is zero.')
                ->danger()
                ->send();
        }

        $invoiceItems[] = [
            'ItemName' => "{$this->section->course->tutor->name} - {$this->section->pricing->name}",
            'Quantity' => $quantity ?? $attendances->count(),
            'UnitPrice' => $this->section->pricing->amount,
            'Passthrough' => $this,
        ];

        SendInvoice::dispatch($this->student, $invoiceItems);

        Notification::make()
            ->title('Invoice Generated')
            ->body("An invoice of value {$formattedInvoiceValue} has been generated successfully.")
            ->success()
            ->send();
    }
}
