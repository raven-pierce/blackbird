@props(['lecture', 'spellOutFormatter', 'loop'])

<div class="flex h-40 w-80 flex-col justify-center rounded bg-gray-900 p-6 transition duration-200 ease-in-out hover:translate-x-1 hover:translate-y-1 hover:ring hover:ring-indigo-500 focus:translate-x-1 focus:translate-y-1 focus:outline-none focus:ring focus:ring-indigo-500">
    <span class="text-xs font-medium uppercase text-indigo-100">Lecture {{ $spellOutFormatter->format($loop->iteration) }}</span>

    <span class="mt-6 text-xl font-bold text-white">{{ $lecture->start_time->englishDayOfWeek }}</span>

    <div class="mt-2 flex items-center justify-between">
        <span class="text-lg text-gray-100">{{ $lecture->start_time->format('h:i A') }}</span>

        <span class="text-xs font-semibold uppercase text-indigo-500">{{ $lecture->duration }} Minutes</span>
    </div>
</div>
