@props(['course'])

<div class="flex hover:cursor-pointer">
    <img src="{{ $course->tutor->profile_photo_url }}" alt="{{ $course->tutor->name }}'s profile picture"
        class="w-1/3 rounded-l object-cover">

    <div class="flex w-2/3 flex-col rounded-r bg-gray-100 p-4 sm:p-6 2xl:p-8">
        <h4 class="font-sans text-xs font-normal uppercase text-gray-700 lg:text-sm 2xl:text-base">
            {{ $course->courseLevel->name }}</h4>

        <h3 class="mt-4 font-sans text-sm font-bold text-gray-900 sm:text-lg lg:mt-6 lg:text-xl 2xl:text-2xl">
            {{ $course->tutor->name }}</h3>
        <h2 class="font-sans text-base font-black text-gray-700 sm:text-xl lg:mt-2 lg:text-2xl 2xl:text-3xl">
            {{ $course->subject->name }}</h2>

        <h4 class="mt-6 font-sans text-xs font-normal uppercase text-gray-700 lg:mt-8 lg:text-sm 2xl:text-base">
            {{ $course->examSession->session->format('F') }} <span
                class="font-light text-gray-500">{{ $course->examSession->session->format('Y') }}</span></h4>
        <h5 class="mt-2 font-sans text-xs font-light text-gray-500 lg:text-sm 2xl:text-base">
            {{ $course->awardingBody->name }}</h5>
    </div>
</div>
