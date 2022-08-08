<x-guest-layout>
    <div class="mx-auto max-w-screen-2xl h-screen overflow-hidden px-24 py-12">
        <nav class="flex items-center justify-between">
            <a href="{{ route('home') }}"
                class="flex items-center space-x-4 rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-opacity-75">
                <x-jet-application-mark class="h-12 w-12" />
                <h1 class="text-xl font-semibold text-indigo-500">Bio<span class="text-amber-500">Helix</span></h1>
            </a>

            @auth
                <x-utilities.links.buttons.default href="{{ route('dashboard') }}">Dashboard</x-utilities.links.buttons.default>
            @else
                <x-utilities.links.buttons.default href="{{ route('login') }}">Log In</x-utilities.links.buttons.default>
            @endauth
        </nav>

        <main class="mt-48 grid grid-cols-2 gap-x-48 px-8">
            <div class="flex flex-col">
                <h2 class="text-5xl font-black">learning,<br />modernized.</h2>

                <p class="mt-4 text-gray-700">Blackbird offers a revitalized, intuitive approach to <span class="font-semibold text-indigo-500">distance learning</span>, with a variety of tools to help you achieve <span class="font-semibold text-indigo-500">academic success</span>. in the British-patented <span class="font-semibold text-indigo-500">International GCSE</span> education system.</p>

                <x-utilities.links.buttons.primary href="{{ auth()->user() ? route('dashboard') : route('register') }}" class="mt-8 w-fit">Get Started</x-utilities.links.buttons.primary>
            </div>

            <div class="relative flex flex-col">
                <img src="{{ asset('images/stock/library.jpg') }}" alt="Stock photo of a library." class="absolute top-32 left-0 w-96 rounded" />

                <x-courses.new-course-card :course="$course" class="absolute top-12 left-48" />
            </div>
        </main>
    </div>
</x-guest-layout>
