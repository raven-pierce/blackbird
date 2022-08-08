<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-500">
                {{ $enrollment->section->course->subject }}</h2>

            <x-utilities.links.tertiary href="{{ route('enrollments.index') }}">Back To Enrollments</x-utilities.links.tertiary>
        </div>
    </x-slot>

    <!--TODO: section swapping and withdrawal-->

    <div class="py-12">
        @if($enrollment->section->lectures->isNotEmpty())
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] gap-16">
                @foreach ($enrollment->section->getEarliestLectures() as $lecture)
                    <div>
                        <x-enrollments.lecture-min :lecture="$lecture" :spellOutFormatter="$spellOutFormatter" :loop="$loop" />
                    </div>
                @endforeach
            </div>

            <div class="mt-24 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h3 class="mb-8 text-lg font-medium text-gray-700">Lectures</h3>
                @livewire('list-lectures')
            </div>
        @else
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full grid grid-cols-4 place-content-center">
                <div class="justify-self-center flex items-center justify-center col-span-full max-w-5xl overflow-hidden shadow-lg rounded-lg">
                    <img src="{{ asset('images/stock/coffee.jpg') }}" alt="A panormaic view of a circular library bookshelf with a vignette." class="w-2/5 object-cover">

                    <div class="p-8 w-3/5 flex flex-col justify-center bg-white h-full">
                            <span class="text-4xl font-bold text-gray-700">No Lectures Planned</span>

                        <p class="mt-8 text-lg font-semibold text-gray-500">
                            Your tutor hasn't added any lectures to their course. This could mean that the term hasn't started yet, so enjoy your time!
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
