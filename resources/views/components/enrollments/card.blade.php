@props(['enrollment', 'loop'])

<a href="{{ route('enrollments.show', $enrollment) }}">
    <div class="flex h-64 w-80 flex-col justify-center rounded bg-gray-900 p-6">
        <div class="flex items-center justify-between text-xs uppercase">
            <span
                class="font-medium text-indigo-100">{{ NumberFormatter::create('en', NumberFormatter::SPELLOUT)->format($loop->iteration) }}</span>

            <span class="font-semibold text-gray-500">{{ $enrollment->section->course->level->name }}</span>
        </div>

        <span class="mt-6 text-xl font-bold text-white">{{ $enrollment->section->course->subject->name }}</span>

        <div class="mt-2 flex items-center justify-between">
            <span class="text-lg text-gray-100">{{ $enrollment->section->course->tutor->name }}</span>
            <span
                class="text-xs font-semibold uppercase text-indigo-500">{{ $enrollment->section->delivery_method->name }}</span>
        </div>

        <div class="mt-4 flex flex-col text-sm font-light text-gray-500">
            @foreach ($enrollment->section->lectures as $lecture)
                <span>{{ $lecture->day->name }} {{ $lecture->start_time->format('h:i A') }}</span>
            @endforeach
        </div>

        <span
            class="mt-6 text-xs font-semibold uppercase text-indigo-500">{{ $enrollment->section->lectures->first()->duration }}
            Minutes</span>
    </div>
</a>
