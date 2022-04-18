<x-guest-layout>
    <div class="p-10">
        <div class="mx-auto flex max-w-4xl items-baseline justify-between xl:max-w-5xl 2xl:max-w-7xl">
            <h1 class="font-sans text-lg font-semibold text-gray-900 xl:text-xl 2xl:text-2xl">Blackbird</h1>

            <div class="hidden items-center justify-between space-x-8 md:flex">
                <a href="" class="font-sans text-sm font-medium text-gray-900 xl:text-base 2xl:text-lg">Courses</a>
                <a href="" class="font-sans text-sm font-medium text-gray-900 xl:text-base 2xl:text-lg">Tutors</a>
                <a href="" class="font-sans text-sm font-medium text-gray-900 xl:text-base 2xl:text-lg">Materials</a>
            </div>

            <a href="" class="font-sans text-lg font-semibold text-gray-900 md:hidden"><span
                    class="fas fa-bars"></span></a>
        </div>

        <div
            class="mx-auto mt-12 grid max-w-2xl grid-cols-12 items-baseline md:px-12 lg:mt-20 lg:max-w-3xl xl:mt-24 xl:max-w-4xl 2xl:mt-28 2xl:max-w-5xl">
            <h1
                class="col-span-8 font-sans text-xl font-bold text-gray-900 sm:text-2xl lg:text-3xl xl:text-4xl 2xl:text-5xl">
                {{ $course->courseLevel->name }}<span
                    class="text-2xl font-black text-gray-900 sm:text-3xl lg:text-4xl xl:text-5xl 2xl:text-6xl"><br>{{ $course->subject->name }}</span>
            </h1>

            <img class="col-span-4 inline w-12 place-self-end rounded-full object-cover sm:row-span-2 sm:w-24 lg:w-28 xl:w-32 2xl:w-36"
                src="{{ $course->tutor->profile_photo_url }}" alt="Dr. John Doe's profile picture.">

            <h2
                class="col-span-8 mt-2 font-sans text-base font-bold text-gray-700 sm:text-lg lg:text-xl xl:text-2xl 2xl:text-3xl">
                {{ $course->tutor->name }}</h2>

            <h3
                class="col-span-4 text-right font-sans text-xs font-medium text-gray-700 sm:col-start-9 sm:row-span-1 sm:row-start-3 sm:self-end sm:text-sm lg:text-base xl:text-lg 2xl:text-xl">
                {{ $course->students->count() }}/{{ $course->seats->sum() }} <span
                    class="text-xs font-light uppercase text-gray-500 lg:text-sm xl:text-base 2xl:text-lg">Seats
                    Taken</span>
            </h3>

            <div class="col-span-12 mt-6 flex flex-col sm:col-span-8">
                <h3 class="font-sans text-xs font-medium uppercase text-gray-700 lg:text-sm xl:text-base 2xl:text-lg">
                    {{ $course->examSession->session->format('F') }} <span
                        class="font-light text-gray-500">{{ $course->examSession->session->format('Y') }}</span></h3>
                <h3 class="font-sans text-xs font-normal text-gray-700 lg:text-sm xl:text-base 2xl:text-lg">
                    {{ $course->awardingBody->name }}</h3>
            </div>
        </div>

        <div
            class="xl:mt-18 mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-y-6 md:px-12 lg:mt-16 lg:max-w-3xl xl:max-w-4xl 2xl:mt-24 2xl:max-w-5xl">
            @foreach ($course->courseVariants as $variant)
                <div
                    class="flex flex-col rounded bg-gray-100 p-4 sm:flex-row sm:items-center sm:justify-between sm:px-8 sm:py-6 xl:px-10 xl:py-8 2xl:px-12 2xl:py-10">
                    <div class="flex flex-col">
                        <h3
                            class="font-sans text-xs font-semibold uppercase text-gray-900 lg:text-sm xl:text-base 2xl:text-lg">
                            Group
                            {{ NumberFormatter::create('en', NumberFormatter::SPELLOUT)->format($loop->iteration) }}
                        </h3>

                        <div class="mt-4 grid w-fit grid-cols-2 items-baseline gap-y-1">
                            @foreach ($variant->variantLectures as $lecture)
                                <h5
                                    class="mr-2 font-mono text-xs font-light text-gray-500 lg:text-sm xl:text-base 2xl:text-lg">
                                    {{ $lecture->start_time->format('h:i A') }}</h5>
                                <h4
                                    class="font-sans text-sm font-medium text-gray-700 lg:text-base xl:text-lg 2xl:text-xl">
                                    {{ $lecture->day->name }}</h4>
                            @endforeach
                        </div>
                    </div>

                    <x-link-button class="mt-4 sm:mt-0">Enroll</x-link-button>
                </div>
            @endforeach
        </div>
    </div>
</x-guest-layout>
