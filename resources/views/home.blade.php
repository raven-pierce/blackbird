<x-guest-layout>
    <div class="mx-auto max-w-screen-2xl h-screen px-24 py-12 overflow-hidden">
        <nav class="flex items-center justify-between">
            <a href="{{ route('home') }}"
                class="flex items-center space-x-4 rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-opacity-75">
                <x-jet-application-mark class="h-12 w-12" />
                <h1 class="text-xl font-semibold text-indigo-500">Bio<span class="text-amber-500">Helix</span></h1>
            </a>

            @auth
                <a href="{{ route('dashboard') }}"
                    class="rounded border border-gray-900 px-8 py-2 text-base font-medium">Dashboard</a>
            @else
                <div class="flex items-center space-x-12">
                    <a href="{{ route('login') }}" class="text-base font-medium">Sign In</a>

                    <a href="{{ route('register') }}"
                        class="rounded border border-gray-900 px-8 py-2 text-base font-medium">Sign Up</a>
                </div>
            @endauth
        </nav>

        <main class="mt-48 grid grid-cols-2 gap-x-48 px-8">
            <div class="flex flex-col">
                <h2 class="text-5xl font-black">learning,<br />modernized.</h2>

                <p class="mt-4 text-gray-700">Blackbird offers a revitalized, intuitive approach to <span class="font-semibold text-indigo-500">distance learning</span>, with a variety of tools to help you achieve <span class="font-semibold text-indigo-500">academic success</span>. in the British-patented <span class="font-semibold text-indigo-500">International GCSE</span> education system.</p>

                <a href="{{ auth()->user() ? route('dashboard') : route('register') }}"
                    class="mt-8 w-fit rounded bg-gray-900 px-8 py-2 text-sm text-white">Get Started</a>
            </div>

            <div class="relative flex flex-col">
                <img src="{{ asset('images/library.jpg') }}" alt="Stock photo of a library." class="absolute top-32 left-0 w-96 rounded" />

                <x-courses.new-course-card :course="$course" class="absolute top-12 left-48" />
            </div>
        </main>
    </div>
</x-guest-layout>
