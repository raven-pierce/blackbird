@props(['course'])

<div class="flex flex-col">
    <div class="flex items-baseline justify-between">
        <h6 class="angled-label bg-gray-900 px-4 py-1.5 font-sans text-sm font-bold uppercase text-gray-50">
            {{ $course->courseLevel->name }}</h6>

        <h6 class="font-sans text-xs font-medium uppercase text-gray-500"><span
                class="text-sm text-gray-700">{{ $course->enrollments->count() }}</span><span
                class="font-extralight">/{{ $course->seats }}</span> Seats Available</h6>
    </div>

    <h1 class="mt-8 font-serif text-5xl font-black text-gray-900">{{ $course->subject->name }}</h1>
    <h2 class="mt-2 font-sans text-2xl font-bold text-gray-700">{{ $course->tutor->name }}</h2>

    <h3 class="mt-8 font-sans text-base font-medium text-gray-700">
        {{ $course->examSession->session->format('F') }} <span
            class="text-sm font-extralight text-gray-500">{{ $course->examSession->session->format('Y') }}</span>
    </h3>
    <h3 class="font-sans text-base font-light text-gray-500">{{ $course->awardingBody->name }}</h3>
</div>
