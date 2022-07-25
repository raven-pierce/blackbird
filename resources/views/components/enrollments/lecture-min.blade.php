@props(['lecture', 'loop'])

<div class="snap-center shrink-0 flex h-40 w-80 flex-col justify-center rounded bg-gray-900 p-6">
    <span class="text-xs font-medium text-indigo-100 uppercase">Lecture {{ NumberFormatter::create('en', NumberFormatter::SPELLOUT)->format($loop->iteration) }}</span>

    <span class="mt-6 text-xl font-bold text-white">{{ $lecture->day->name }}</span>

    <div class="mt-2 flex items-center justify-between">
        <span class="text-lg text-gray-100">{{ $lecture->start_time->format('h:i A') }}</span>

        <span class="text-xs font-semibold uppercase text-indigo-500">{{ $lecture->duration }} Minutes</span>
    </div>
</div>
