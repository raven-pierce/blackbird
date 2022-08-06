@props(['enrollment', 'spellOutFormatter', 'currencyFormatter', 'loop'])

<div class="flex h-60 w-80 flex-col justify-center rounded bg-gray-900 p-6">
    <div class="flex justify-between text-xs uppercase">
        <span class="font-medium text-indigo-100">{{ $spellOutFormatter->format($loop->iteration) }}</span>

        <div class="flex flex-col text-right font-semibold text-gray-500">
            <span>{{ $enrollment->section->course->subject }}</span>
            <span>{{ $enrollment->section->course->tutor->name }}</span>
        </div>
    </div>

    <span
        class="mt-6 text-xl font-bold text-indigo-500">{{ $currencyFormatter->formatCurrency($enrollment->unitPricing() * $enrollment->unpaidAttendances->count(), 'USD') }}</span>

    <div class="mt-4 flex justify-between text-xs font-semibold uppercase">
        <div class="flex flex-col text-gray-500">
            <span>{{ $enrollment->section->course->course_level }}</span>
            <span>{{ $currencyFormatter->formatCurrency($enrollment->unitPricing(), 'USD') }} per lecture</span>
        </div>

        <span
            class="text-right text-indigo-500">{{ $spellOutFormatter->format($enrollment->unpaidAttendances->count()) }}
            {{ $enrollment->unpaidAttendances->count() > 1 ? 'Lectures' : 'Lecture' }}</span>
    </div>

    <form method="POST" action="{{ route('checkout') }}">
        @csrf

        <input type="hidden" name="enrollment" value="{{ $enrollment->id }}">

        <x-utilities.forms.primary class="mt-6 w-full">Pay Now</x-utilities.forms.primary>
    </form>
</div>
