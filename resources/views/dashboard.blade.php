<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-500">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full grid grid-cols-4 place-content-center">
            @if($lectures->isNotEmpty())
                <h1 class="col-span-full text-3xl font-black text-gray-900">
                    {{ $greeting }}, <span class="text-indigo-500">{{ auth()->user()->name }}</span>
                </h1>

                <span class="mt-4 col-span-full text-lg font-medium text-gray-500">Here's what you have planned for today.</span>

                <div class="mt-16 grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] gap-16">
                    @foreach($lectures as $lecture)
                        <div class="flex flex-col justify-center rounded-md bg-gray-900 p-6">
                            <div class="flex items-center justify-between text-xs uppercase">
                                <span class="font-medium text-indigo-100">{{ $spellOutFormatter->format($loop->iteration) }}</span>

                                <span class="font-semibold text-gray-500">{{ $lecture->section->course->tutor->name     }}</span>
                            </div>

                            <span class="mt-6 text-xl font-bold text-white">{{ $lecture->start_time->format('h:i A') }}</span>

                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-lg text-gray-100">{{ $lecture->section->course->subject }}</span>
                                <span class="text-xs font-semibold uppercase text-indigo-500">{{ $lecture->section->delivery_method    }}</span>
                            </div>

                            <div class="mt-4 flex flex-col text-sm font-light text-gray-500">{{ $lecture->section->course->course_level }}</div>

                            <span class="mt-6 text-xs font-semibold uppercase text-indigo-500">{{ $lecture->duration }} Minutes</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="justify-self-center flex items-center justify-center col-span-full max-w-5xl overflow-hidden shadow-lg rounded-lg">
                    <img src="{{ asset('images/stock/coffee.jpg') }}" alt="A panormaic view of a circular library bookshelf with a vignette." class="w-2/5 object-cover">

                    <div class="p-8 w-3/5 flex flex-col justify-center bg-white h-full">
                        <span class="text-4xl font-bold text-gray-700">No Lectures Today</span>

                        <p class="mt-8 text-lg font-semibold text-gray-500">
                            You've got no lectures left today. Have a cup of coffee and enjoy the rest of your day!
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
