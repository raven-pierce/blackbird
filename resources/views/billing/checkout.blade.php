<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Blackbird') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Bitter:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @paddleJS
</head>

<body
    class="font-sans antialiased text-white h-screen bg-[image:linear-gradient(120deg,theme(colors.gray.900)_65%,theme(colors.indigo.500)_35%)]">
    <div class="px-24 py-12 m-auto h-full flex max-w-screen-2xl justify-center">
        <div class="flex flex-col w-3/5">
            <div class="flex space-x-4 items-center">
                <a href="{{ route('dashboard') }}">
                    <x-assets.logo class="w-12 h-12" />
                </a>

                <a href="{{ route('dashboard') }}">
                    <span class="text-xl font-semibold">{{ config('app.name', 'Blackbird') }}</span>
                </a>
            </div>

            <div class="flex flex-col justify-center h-full">
                <span
                    class="text-sm font-semibold uppercase text-gray-500">{{ $enrollment->section->course->level->name }}</span>

                <span
                    class="mt-4 text-5xl font-black leading-tight">{{ $enrollment->section->course->subject->name }}</span>
                <span
                    class="text-5xl font-black text-indigo-500 leading-tight">{{ $enrollment->section->course->tutor->name }}</span>

                <div class="mt-16 flex space-x-8 items-center">
                    <span
                        class="text-sm font-semibold uppercase text-indigo-500">{{ $spellOutFormatter->format($enrollment->unpaidAttendances->count()) }}
                        {{ $enrollment->unpaidAttendances->count() > 1 ? 'Lectures' : 'Lecture' }}</span>

                    <span class="text-sm uppercase text-gray-500">{{ $currencyFormatter->formatCurrency($enrollment->unitPricing(), "USD") }} per lecture</span>
                </div>

                <x-links.tertiary href="{{ route('billing.index') }}" class="mt-16 text-xs font-semibold uppercase">Back To Dashboard</x-links.tertiary>
            </div>
        </div>

        <div class="flex w-2/5 items-center justify-center">
            <x-paddle-checkout :override="$enrollment->paddlePayLink()" class="w-96 bg-white p-4 rounded h-fit" />
        </div>
    </div>
</body>

</html>
