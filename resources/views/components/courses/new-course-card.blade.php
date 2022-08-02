@props(['course'])

<a href="{{ route('courses.show', $course) }}" {{ $attributes->merge(['class' => 'group focus:outline-none']) }}>
    <div class="flex h-64 w-80 flex-col justify-center rounded bg-gray-900 p-6 transition duration-200 ease-in-out group-hover:translate-x-1 group-hover:translate-y-1 group-hover:ring group-hover:ring-indigo-500 group-focus:translate-x-1 group-focus:translate-y-1 group-focus:outline-none group-focus:ring group-focus:ring-indigo-500">
        <div class="flex items-center justify-between text-xs uppercase">
            <span class="font-medium text-indigo-100">New</span>

            <span class="font-semibold text-gray-500">{{ $course->section->course->level->name }}</span>
        </div>

        <span class="mt-6 text-xl font-bold text-white">{{ $course->section->course->subject->name }}</span>

        <div class="mt-2 flex items-center justify-between">
            <span class="text-lg text-gray-100">{{ $course->section->course->tutor->name }}</span>
            <span class="text-xs font-semibold uppercase text-indigo-500">{{ $course->section->delivery_method->name }}</span>
        </div>

        <div class="mt-4 flex flex-col text-sm font-light text-gray-500">
            @foreach ($course->section->getLecturesThisWeek()->isNotEmpty() ? $course->section->getLecturesThisWeek() : $course->section->getLecturesInWeeks() as $lecture)
                <span>{{ $lecture->start_time->englishDayOfWeek }} {{ $lecture->start_time->format('h:i A') }}</span>
            @endforeach
        </div>

        <span class="mt-6 text-xs font-semibold uppercase text-indigo-500">{{ $course->section->lectures->avg('duration') }} Minutes</span>
    </div>
</a>
