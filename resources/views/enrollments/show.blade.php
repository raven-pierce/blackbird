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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] gap-16">
            @foreach ($enrollment->section->getEarliestLectures() as $lecture)
                <div>
                    <x-enrollments.lecture-min :lecture="$lecture" :spellOutFormatter="$spellOutFormatter" :loop="$loop" />
                </div>
            @endforeach
        </div>

        <div class="mt-32 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="mb-8 text-lg font-medium text-gray-700">Lectures</h3>
            @livewire('list-lectures')
        </div>
    </div>
</x-app-layout>
