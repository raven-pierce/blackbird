@props(['course'])

<a href="{{ route('courses.show', $course) }}" {{ $attributes }}>
    <div class="flex h-64 w-80 flex-col justify-center rounded-md bg-gray-900 p-6">
        <div class="flex items-center justify-between text-xs uppercase">
            <span class="font-medium text-indigo-100">New</span>

            <span class="font-semibold text-gray-500">{{ $course->course_level }}</span>
        </div>

        <span class="mt-6 text-xl font-bold text-white">{{ $course->name }}</span>

        <div class="mt-2 flex items-center justify-between">
            <span class="text-lg text-gray-100">{{ $course->tutor->name }}</span>
            <span class="text-xs font-semibold uppercase text-indigo-500">{{ $course->sections->first()->delivery_method }}</span>
        </div>

        <div class="mt-4 flex flex-col text-sm font-light text-gray-500">
            @foreach ($course->sections->first()->getEarliestLectures() as $lecture)
                <span>{{ $lecture->start_time->englishDayOfWeek }} {{ $lecture->start_time->format('h:i A') }}</span>
            @endforeach
        </div>

        <span class="mt-6 text-xs font-semibold uppercase text-indigo-500">{{ $course->sections->first()->lectures->avg('duration') }} Minutes</span>
    </div>
</a>
