<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-500">
                {{ $enrollment->section->course->subject->name }}</h2>

            <x-links.tertiary href="{{ route('enrollments.index') }}">Back To Enrollments</x-links.tertiary>
        </div>
    </x-slot>

    <!--TODO: section swapping and withdrawal-->

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-[repeat(auto-fill,minmax(20rem,1fr))] gap-16">
            @foreach ($enrollment->section->getLecturesThisWeek()->isNotEmpty() ? $enrollment->section->getLecturesThisWeek() : $enrollment->section->getLecturesInWeeks() as $lecture)
                <div>
                    <x-enrollments.lecture-min :lecture="$lecture" :spellOutFormatter="$spellOutFormatter" :loop="$loop" />
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
