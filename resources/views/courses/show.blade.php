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

            @auth
                <x-link-button href="{{ route('dashboard') }}">Dashboard<i
                        class="fa-solid fa-arrow-right ml-2 -rotate-45"></i></x-link-button>
            @else
                <div class="flex items-baseline justify-between space-x-8">
                    <a href="{{ route('login') }}" class="font-sans text-sm font-normal text-gray-700">Sign In</a>

                    <x-link-button href="{{ route('register') }}">Sign Up<i
                            class="fa-solid fa-arrow-right ml-2 -rotate-45"></i></x-link-button>
                </div>
            @endauth
        </div>

        <div class="col-span-12 col-start-1 row-span-5 row-start-2 grid grid-cols-2 xl:col-span-11 xl:col-start-2">
            <div class="flex flex-col border-r border-gray-100 px-24 py-16">
                <x-course.metadata :course="$course" />

                @guest
                    <x-cta.sign-up class="mt-16" />
                @endguest

                <x-cta.scroll-down class="mt-16" />
            </div>

            <div class="grid grid-cols-1 gap-y-16 overflow-y-scroll px-24 py-16">
                @foreach ($course->courseVariants as $variant)
                    <x-course.variant :variant="$variant" :loop="$loop" />
                @endforeach
            </div>
        </div>
    </div>
</x-guest-layout>
