<x-guest-layout>
    <div
        class="mx-auto grid h-screen grid-cols-12 grid-rows-[0fr_repeat(5,minmax(0,1fr))] xl:grid-cols-[0fr_repeat(11,minmax(0,1fr))]">
        <div
            class="col-span-1 row-span-6 hidden flex-col items-center justify-center space-y-4 border-r border-gray-100 px-6 xl:flex">
            <a href="#" class="font-mono text-sm font-medium text-gray-700">01</a>
            <a href="#" class="font-mono text-sm font-light text-gray-500">02</a>
            <a href="#" class="font-mono text-sm font-light text-gray-500">03</a>
            <a href="#" class="font-mono text-sm font-light text-gray-500">04</a>
        </div>

        <div
            class="col-span-12 col-start-1 row-start-1 flex h-fit items-baseline justify-between border-b border-gray-100 px-16 xl:col-span-11 xl:col-start-2 xl:px-0 xl:pr-16">
            <div class="flex items-baseline">
                <a href="#"
                    class="bg-gray-900 px-4 pt-12 pb-6 font-sans text-base font-semibold uppercase text-gray-50">Blackbird</a>

                <div class="ml-8 flex space-x-12">
                    <a href="#" class="pt-12 pb-6 font-sans text-base font-bold text-gray-900">Home</a>
                    <a href="#" class="pt-12 pb-6 font-sans text-base font-semibold text-gray-700">Courses</a>
                    <a href="#" class="pt-12 pb-6 font-sans text-base font-semibold text-gray-700">Tutors</a>
                    <a href="#" class="pt-12 pb-6 font-sans text-base font-semibold text-gray-700">Materials</a>
                </div>
            </div>

            <a href="{{ route('register') }}"
                class="justify-self-end bg-gray-900 px-8 py-4 font-sans text-sm font-semibold text-gray-50">Sign
                Up<i class="fa-solid fa-arrow-right ml-2 -rotate-45"></i></a>
        </div>

        <div class="col-span-12 col-start-1 row-span-5 row-start-2 grid grid-cols-2 xl:col-span-11 xl:col-start-2">
            <div class="flex flex-col border-r border-gray-100 px-24 py-16">
                <div class="flex items-baseline justify-between">
                    <h6 class="angled-label bg-gray-900 px-4 py-1.5 font-sans text-sm font-bold uppercase text-gray-50">
                        Ordinary
                        Level</h6>

                    <h6 class="font-sans text-xs font-medium uppercase text-gray-500"><span
                            class="text-sm text-gray-700">{{ $course->enrollments->count() }}</span><span
                            class="font-extralight">/{{ $course->seats }}</span> Seats Available</h6>
                </div>

                <h1 class="mt-8 font-serif text-5xl font-black text-gray-900">{{ $course->subject->name }}</h1>
                <h2 class="mt-2 font-sans text-2xl font-bold text-gray-700">{{ $course->tutor->name }}</h2>

                <h3 class="mt-8 font-sans text-base font-medium text-gray-700">
                    {{ $course->examSession->session->format('F') }}
                    <span
                        class="text-sm font-extralight text-gray-500">{{ $course->examSession->session->format('Y') }}</span>
                </h3>
                <h3 class="font-sans text-base font-light text-gray-500">{{ $course->awardingBody->name }}</h3>

                <div
                    class="mt-16 flex w-fit items-baseline justify-between space-x-16 border border-l-4 border-gray-900/25 border-l-gray-900 p-2 pl-4">
                    <p class="font-sans text-sm font-light text-gray-500">Create an account to enroll.</p>

                    <a href="{{ route('register') }}"
                        class="justify-self-end bg-gray-900 px-6 py-1.5 font-sans text-sm font-semibold text-gray-50">Sign
                        Up<i class="fa-solid fa-arrow-right ml-2 -rotate-45"></i></a>
                </div>

                <a href="#" class="mt-16 font-sans text-xs font-extralight uppercase text-gray-500"><i
                        class="fa-solid fa-arrow-down mr-4"></i>Scroll Down</a>
            </div>

            <div class="grid grid-cols-1 overflow-y-scroll px-24 py-16">
                @foreach ($course->courseVariants as $variant)
                    <x-course-variant-card :variant="$variant" :loop="$loop" />
                @endforeach
            </div>
        </div>
    </div>
</x-guest-layout>
