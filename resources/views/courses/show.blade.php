<x-guest-layout>
    <div class="p-10">
        <div class="mx-auto w-full max-w-4xl xl:max-w-5xl 2xl:max-w-7xl flex justify-between items-baseline">
            <h1 class="font-sans font-semibold text-lg xl:text-xl 2xl:text-2xl text-gray-900">Blackbird</h1>

            <div class="hidden md:flex justify-between items-center space-x-8">
                <a href="" class="font-sans font-medium text-sm xl:text-base 2xl:text-lg text-gray-900">Courses</a>
                <a href="" class="font-sans font-medium text-sm xl:text-base 2xl:text-lg text-gray-900">Tutors</a>
                <a href="" class="font-sans font-medium text-sm xl:text-base 2xl:text-lg text-gray-900">Materials</a>
            </div>

            <a href="" class="md:hidden font-sans font-semibold text-lg text-gray-900"><span class="fas fa-bars"></span></a>
        </div>

        <div
            class="md:px-12 mx-auto mt-12 lg:mt-20 xl:mt-24 2xl:mt-28 w-full max-w-2xl lg:max-w-3xl xl:max-w-4xl 2xl:max-w-5xl grid grid-cols-12 items-baseline">
            <h1 class="col-span-8 font-sans font-bold text-xl sm:text-2xl lg:text-3xl xl:text-4xl 2xl:text-5xl text-gray-900">
                {{ $course->courseLevel->name }}<span
                    class="font-black text-2xl sm:text-3xl lg:text-4xl xl:text-5xl 2xl:text-6xl text-gray-900"><br>{{ $course->subject->name }}</span>
            </h1>

            <img
                class="col-span-4 sm:row-span-2 place-self-end inline rounded-full w-12 sm:w-24 lg:w-28 xl:w-32 2xl:w-36 object-cover"
                src="{{ $course->tutor->profile_photo_url }}"
                alt="Dr. John Doe's profile picture.">

            <h2 class="col-span-8 mt-2 font-sans font-bold text-base sm:text-lg lg:text-xl xl:text-2xl 2xl:text-3xl text-gray-700">
                {{ $course->tutor->name }}</h2>

            <h3 class="col-span-4 sm:row-span-1 sm:row-start-3 sm:col-start-9 sm:self-end text-right font-sans font-medium text-xs sm:text-sm lg:text-base xl:text-lg 2xl:text-xl text-gray-700">
                {{ $course->students->count() }}/{{ $course->seats->sum() }} <span
                    class="font-light text-xs lg:text-sm xl:text-base 2xl:text-lg text-gray-500 uppercase">Seats Taken</span>
            </h3>

            <div class="col-span-12 sm:col-span-8 mt-6 w-full flex flex-col">
                <h3 class="font-sans font-medium text-xs lg:text-sm xl:text-base 2xl:text-lg text-gray-700 uppercase">
                    {{ $course->examSession->session->format('F') }} <span
                        class="font-light text-gray-500">{{ $course->examSession->session->format('Y') }}</span></h3>
                <h3 class="font-sans font-normal text-xs lg:text-sm xl:text-base 2xl:text-lg text-gray-700">{{ $course->awardingBody->name }}</h3>
            </div>
        </div>

        <div
            class="md:px-12 mx-auto mt-10 lg:mt-16 xl:mt-18 2xl:mt-24 w-full max-w-2xl lg:max-w-3xl xl:max-w-4xl 2xl:max-w-5xl grid grid-cols-1 gap-y-6">
            @foreach($course->courseVariants as $variant)
                <div
                    class="w-full flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-100 p-4 sm:px-8 sm:py-6 xl:px-10 xl:py-8 2xl:px-12 2xl:py-10 rounded">
                    <div class="flex flex-col">
                        <h3 class="font-sans font-semibold text-xs lg:text-sm xl:text-base 2xl:text-lg text-gray-900 uppercase">
                            Group {{ NumberFormatter::create('en', NumberFormatter::SPELLOUT)->format($loop->iteration) }}</h3>

                        <div class="mt-4 w-fit grid grid-cols-2 gap-y-1 items-baseline">
                            @foreach($variant->variantLectures as $lecture)
                                <h5 class="font-mono font-light text-xs lg:text-sm xl:text-base 2xl:text-lg text-gray-500 mr-2">{{ $lecture->start_time->format('h:i A') }}</h5>
                                <h4 class="font-sans font-medium text-sm lg:text-base xl:text-lg 2xl:text-xl text-gray-700">{{ $lecture->day->name }}</h4>
                            @endforeach
                        </div>
                    </div>

                    <x-link-button class="mt-4 sm:mt-0">Enroll</x-link-button>
                </div>
            @endforeach
        </div>
    </div>
</x-guest-layout>
