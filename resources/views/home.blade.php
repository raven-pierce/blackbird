<x-guest-layout>
    <div class="mx-auto max-w-screen-2xl px-24 py-12">
        <nav class="flex items-center justify-between">
            <a href="{{ route('home') }}"
                class="flex items-center space-x-4 rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-opacity-75">
                <img src="../images/logo.svg" alt="Blackbird Logo" />
                <span class="text-xl font-semibold">Blackbird</span>
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

        <main class="mt-32 grid grid-cols-2 gap-x-48 px-8">
            <div class="flex flex-col">
                <span class="text-5xl font-black">learning,<br />modernized.</span>

                <p class="mt-4 text-gray-700">Dolor molestiae voluptatem quae. Ut eum sint fugit similique. Ut mollitia
                    natus
                    eius et debitis doloribus qui.</p>

                <a href="{{ auth()->user() ? route('dashboard') : route('register') }}"
                    class="mt-8 w-fit rounded bg-gray-900 px-8 py-2 text-sm text-white">Get Started</a>
            </div>

            <div class="relative flex flex-col">
                <img src="../images/library.jpg" alt="Stock photo of a library."
                    class="absolute top-32 left-0 w-96 rounded" />

                <div class="absolute top-12 left-48 flex h-64 flex-col justify-center rounded bg-gray-900 p-6">
                    <span class="font-medium text-indigo-100">New Course</span>

                    <span class="mt-6 text-xl font-bold text-white">Year 10 Biology</span>

                    <span class="mt-2 text-lg text-gray-100">Dr. John Doe</span>

                    <span class="mt-4 text-sm font-light text-gray-500">Cambridge Assessment International
                        Examinations</span>

                    <span class="mt-6 text-xs font-semibold uppercase text-indigo-500">90 min/session</span>
                </div>
            </div>
        </main>
    </div>
</x-guest-layout>
